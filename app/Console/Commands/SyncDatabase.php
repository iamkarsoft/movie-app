<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SyncDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync
                            {--production : Sync from the latest production backup}
                            {--local : Sync from the latest local backup}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the database from backup (merge records, never delete)';

    protected array $excludeTables = ['migrations', 'password_resets', 'password_reset_tokens', 'failed_jobs', 'personal_access_tokens'];

    protected array $userIdMapping = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $environment = $this->getEnvironment();

        $this->info("Syncing database from latest {$environment} backup (merge mode)...");

        if (! $this->option('force')) {
            $this->warn('This will UPDATE existing records and CREATE new ones. No records will be deleted.');

            if (! $this->confirm('Continue?')) {
                $this->info('Sync cancelled.');
                return 0;
            }
        }

        $disk = Storage::disk('backups');
        $appName = config('app.name');
        $backups = $this->getAvailableBackups($disk, $appName, $environment);

        if (empty($backups)) {
            $this->error("No {$environment} backups found.");
            return 1;
        }

        // Use the latest backup
        $backupFile = $backups[0];
        $this->info('Using backup: ' . basename($backupFile));

        return $this->syncFromBackup($disk, $backupFile);
    }

    /**
     * Get available backup files filtered by environment.
     */
    protected function getAvailableBackups($disk, string $appName, string $environment): array
    {
        $files = $disk->allFiles($appName);

        $backups = array_filter($files, function ($file) use ($environment) {
            $filename = basename($file);
            return pathinfo($file, PATHINFO_EXTENSION) === 'zip'
                && str_starts_with($filename, $environment . '-');
        });

        usort($backups, fn($a, $b) => $disk->lastModified($b) - $disk->lastModified($a));

        return array_values($backups);
    }

    /**
     * Sync from the backup file using merge logic.
     */
    protected function syncFromBackup($disk, string $backupFile): int
    {
        $tempDir = storage_path('app/backup-temp/sync-' . time());
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            // Extract backup
            $localZipPath = $tempDir . '/backup.zip';
            file_put_contents($localZipPath, $disk->get($backupFile));

            $zip = new ZipArchive;
            if ($zip->open($localZipPath) !== true) {
                throw new \Exception('Could not open backup archive.');
            }
            $zip->extractTo($tempDir);
            $zip->close();

            // Find SQL file
            $sqlFile = $this->findSqlFile($tempDir);
            if (! $sqlFile) {
                throw new \Exception('No SQL dump file found in backup.');
            }

            $this->info('Processing SQL file...');

            // Import to temp database and sync
            $this->syncWithMerge($sqlFile);

            $this->info('Database synced successfully (merge mode)!');

            return 0;
        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            return 1;
        } finally {
            $this->deleteDirectory($tempDir);
        }
    }

    /**
     * Sync using merge logic - update or create, never delete.
     */
    protected function syncWithMerge(string $sqlFile): void
    {
        $tempDatabase = config('database.connections.mysql.database') . '_temp_sync';
        $mainDatabase = config('database.connections.mysql.database');

        try {
            // Create temp database
            $this->line('Creating temporary database...');
            DB::statement("DROP DATABASE IF EXISTS `{$tempDatabase}`");
            DB::statement("CREATE DATABASE `{$tempDatabase}`");

            // Import SQL to temp database
            $this->line('Importing backup to temporary database...');
            $this->importToDatabase($tempDatabase, $sqlFile);

            // Build user ID mapping by email FIRST
            $this->line('Building user mapping by email...');
            $this->buildUserIdMapping($tempDatabase, $mainDatabase);
            $this->line('Mapped ' . count($this->userIdMapping) . ' users by email.');

            // Get tables from temp database
            $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [$tempDatabase]);

            // Define table sync order (users first, then tables with user_id)
            $priorityTables = ['users', 'movies'];
            $tablesWithUserFk = ['movie_user'];

            $this->line('Merging tables...');
            $bar = $this->output->createProgressBar(count($tables));

            // Sync priority tables first
            foreach ($priorityTables as $tableName) {
                if (Schema::hasTable($tableName)) {
                    $this->mergeTable($tempDatabase, $mainDatabase, $tableName);
                }
                $bar->advance();
            }

            // Sync tables with user foreign keys (with remapping)
            foreach ($tablesWithUserFk as $tableName) {
                if (Schema::hasTable($tableName)) {
                    $this->mergeTableWithUserMapping($tempDatabase, $mainDatabase, $tableName);
                }
                $bar->advance();
            }

            // Sync remaining tables
            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;

                if (in_array($tableName, $this->excludeTables)) {
                    $bar->advance();
                    continue;
                }

                if (in_array($tableName, $priorityTables) || in_array($tableName, $tablesWithUserFk)) {
                    continue; // Already processed
                }

                if (! Schema::hasTable($tableName)) {
                    $bar->advance();
                    continue;
                }

                $this->mergeTable($tempDatabase, $mainDatabase, $tableName);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

        } finally {
            // Cleanup temp database
            DB::statement("DROP DATABASE IF EXISTS `{$tempDatabase}`");
            $this->line('Temporary database cleaned up.');
        }
    }

    /**
     * Build a mapping of production user IDs to local user IDs by email.
     */
    protected function buildUserIdMapping(string $tempDb, string $mainDb): void
    {
        $this->userIdMapping = [];

        // Get users from temp (production) database
        $prodUsers = DB::select("SELECT id, email FROM `{$tempDb}`.`users`");

        foreach ($prodUsers as $prodUser) {
            // Find matching local user by email
            $localUser = DB::selectOne(
                "SELECT id FROM `{$mainDb}`.`users` WHERE email = ?",
                [$prodUser->email]
            );

            if ($localUser) {
                $this->userIdMapping[$prodUser->id] = $localUser->id;
            }
        }
    }

    /**
     * Merge a table that has user_id foreign key, remapping to local user IDs.
     */
    protected function mergeTableWithUserMapping(string $tempDb, string $mainDb, string $table): void
    {
        // Get records from temp database
        $records = DB::select("SELECT * FROM `{$tempDb}`.`{$table}`");

        foreach ($records as $record) {
            $data = (array) $record;

            // Remap user_id if present and mapping exists
            if (isset($data['user_id']) && isset($this->userIdMapping[$data['user_id']])) {
                $data['user_id'] = $this->userIdMapping[$data['user_id']];
            } else if (isset($data['user_id'])) {
                // Skip records for users that don't exist locally
                continue;
            }

            // Check if record already exists (by movie_id + user_id for pivot)
            if ($table === 'movie_user') {
                $exists = DB::selectOne(
                    "SELECT id FROM `{$mainDb}`.`{$table}` WHERE movie_id = ? AND user_id = ?",
                    [$data['movie_id'], $data['user_id']]
                );

                if ($exists) {
                    // Update existing record
                    unset($data['id']);
                    DB::table($table)->where('id', $exists->id)->update($data);
                } else {
                    // Insert new record (let it auto-generate ID)
                    unset($data['id']);
                    DB::table($table)->insert($data);
                }
            }
        }
    }

    /**
     * Merge a single table from temp to main database.
     */
    protected function mergeTable(string $tempDb, string $mainDb, string $table): void
    {
        // Get primary key
        $primaryKey = $this->getPrimaryKey($mainDb, $table);

        if (! $primaryKey) {
            // No primary key, skip table
            return;
        }

        // Get columns
        $columns = DB::select(
            "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
            [$mainDb, $table]
        );
        $columnNames = array_map(fn($col) => $col->COLUMN_NAME, $columns);

        if (empty($columnNames)) {
            return;
        }

        $columnList = implode(', ', array_map(fn($c) => "`{$c}`", $columnNames));
        $updateList = implode(', ', array_map(fn($c) => "`{$c}` = VALUES(`{$c}`)", 
            array_filter($columnNames, fn($c) => $c !== $primaryKey)
        ));

        // Use INSERT ... ON DUPLICATE KEY UPDATE
        $sql = "INSERT INTO `{$mainDb}`.`{$table}` ({$columnList})
                SELECT {$columnList} FROM `{$tempDb}`.`{$table}`
                ON DUPLICATE KEY UPDATE {$updateList}";

        DB::statement($sql);
    }

    /**
     * Get primary key column for a table.
     */
    protected function getPrimaryKey(string $database, string $table): ?string
    {
        $result = DB::select(
            "SELECT COLUMN_NAME FROM information_schema.COLUMNS 
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_KEY = 'PRI'",
            [$database, $table]
        );

        return $result[0]->COLUMN_NAME ?? null;
    }

    /**
     * Import SQL file to a database.
     */
    protected function importToDatabase(string $database, string $sqlFile): void
    {
        $config = config('database.connections.mysql');

        // Read SQL and clean it up for import to temp database
        $sql = file_get_contents($sqlFile);
        
        // Remove mysqldump warnings
        $sql = preg_replace('/^mysqldump:.*$/mi', '', $sql);
        // Remove USE statements
        $sql = preg_replace('/^USE\s+`?[^`;]+`?;?\s*$/mi', '', $sql);
        // Remove CREATE DATABASE statements  
        $sql = preg_replace('/^CREATE DATABASE.*$/mi', '', $sql);
        
        // Write modified SQL to temp file
        $modifiedSqlFile = $sqlFile . '.modified';
        file_put_contents($modifiedSqlFile, $sql);

        // Use MYSQL_PWD env var to avoid password warning
        $command = sprintf(
            'MYSQL_PWD=%s mysql --host=%s --port=%s --user=%s %s < %s 2>&1',
            escapeshellarg($config['password']),
            escapeshellarg($config['host']),
            escapeshellarg($config['port'] ?? 3306),
            escapeshellarg($config['username']),
            escapeshellarg($database),
            escapeshellarg($modifiedSqlFile)
        );

        exec($command, $output, $exitCode);

        // Cleanup modified file
        @unlink($modifiedSqlFile);

        if ($exitCode !== 0) {
            // Filter out password warnings from error output
            $errors = array_filter($output, fn($line) => ! str_contains($line, 'Using a password'));
            if (! empty($errors)) {
                throw new \Exception('Failed to import SQL: ' . implode("\n", $errors));
            }
        }
    }

    /**
     * Find SQL file in directory.
     */
    protected function findSqlFile(string $directory): ?string
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'sql') {
                return $file->getPathname();
            }
        }

        return null;
    }

    /**
     * Recursively delete a directory.
     */
    protected function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    /**
     * Get the environment from options or detect from APP_ENV.
     */
    protected function getEnvironment(): string
    {
        if ($this->option('production')) {
            return 'production';
        }

        if ($this->option('local')) {
            return 'local';
        }

        $appEnv = config('app.env');
        return $appEnv === 'production' ? 'production' : 'local';
    }
}
