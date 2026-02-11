<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore
                            {--production : Restore from a production backup}
                            {--local : Restore from a local backup}
                            {--backup= : The backup file to restore (optional, will list available if not provided)}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore the database from a backup file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = Storage::disk('backups');
        $appName = config('app.name');
        $environment = $this->getEnvironment();

        // Get all backup files filtered by environment
        $backups = $this->getAvailableBackups($disk, $appName, $environment);

        if (empty($backups)) {
            $this->error("No {$environment} backups found.");

            return 1;
        }

        // If no backup specified, show list and let user choose
        $backupFile = $this->option('backup');

        if (! $backupFile) {
            $this->info('Available backups:');
            $this->newLine();

            foreach ($backups as $index => $backup) {
                $size = $this->formatBytes($disk->size($backup));
                $date = date('Y-m-d H:i:s', $disk->lastModified($backup));
                $this->line(sprintf('[%d] %s (%s) - %s', $index + 1, basename($backup), $size, $date));
            }

            $this->newLine();
            $choice = $this->ask('Enter the number of the backup to restore (or "q" to quit)');

            if ($choice === 'q' || $choice === null) {
                $this->info('Restore cancelled.');

                return 0;
            }

            $index = (int) $choice - 1;

            if (! isset($backups[$index])) {
                $this->error('Invalid selection.');

                return 1;
            }

            $backupFile = $backups[$index];
        } else {
            // Find the backup file matching the provided name
            $found = false;
            foreach ($backups as $backup) {
                if (basename($backup) === $backupFile || $backup === $backupFile) {
                    $backupFile = $backup;
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $this->error("Backup file not found: {$backupFile}");

                return 1;
            }
        }

        // Confirm restore
        if (! $this->option('force')) {
            $this->warn('WARNING: This will overwrite your current database!');
            if (! $this->confirm('Are you sure you want to restore from: '.basename($backupFile).'?')) {
                $this->info('Restore cancelled.');

                return 0;
            }
        }

        return $this->restoreFromBackup($disk, $backupFile);
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

        // Default to current environment
        $appEnv = config('app.env');

        return $appEnv === 'production' ? 'production' : 'local';
    }

    /**
     * Get available backup files sorted by date (newest first).
     */
    protected function getAvailableBackups($disk, string $appName, string $environment): array
    {
        $files = $disk->allFiles($appName);

        // Filter for zip files with the correct environment prefix
        $backups = array_filter($files, function ($file) use ($environment) {
            $filename = basename($file);

            return pathinfo($file, PATHINFO_EXTENSION) === 'zip'
                && str_starts_with($filename, $environment.'-');
        });

        // Sort by last modified (newest first)
        usort($backups, fn ($a, $b) => $disk->lastModified($b) - $disk->lastModified($a));

        return array_values($backups);
    }

    /**
     * Restore database from the backup file.
     */
    protected function restoreFromBackup($disk, string $backupFile): int
    {
        $this->info('Starting restore from: '.basename($backupFile));

        // Create temp directory
        $tempDir = storage_path('app/backup-temp/restore-'.time());
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            // Download/copy the backup file to temp
            $localZipPath = $tempDir.'/backup.zip';
            file_put_contents($localZipPath, $disk->get($backupFile));

            // Extract the zip
            $zip = new ZipArchive;
            if ($zip->open($localZipPath) !== true) {
                throw new \Exception('Could not open backup archive.');
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Find the SQL file
            $sqlFile = $this->findSqlFile($tempDir);

            if (! $sqlFile) {
                throw new \Exception('No SQL dump file found in backup.');
            }

            $this->info('Found SQL file: '.basename($sqlFile));

            // Restore the database
            $this->restoreDatabase($sqlFile);

            $this->info('Database restored successfully!');

            return 0;
        } catch (\Exception $e) {
            $this->error('Restore failed: '.$e->getMessage());

            return 1;
        } finally {
            // Cleanup temp directory
            $this->deleteDirectory($tempDir);
        }
    }

    /**
     * Find SQL file in the extracted backup.
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
     * Restore the database from SQL file.
     */
    protected function restoreDatabase(string $sqlFile): void
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        switch ($config['driver']) {
            case 'mysql':
                $this->restoreMysql($config, $sqlFile);
                break;
            case 'pgsql':
                $this->restorePostgres($config, $sqlFile);
                break;
            case 'sqlite':
                $this->restoreSqlite($config, $sqlFile);
                break;
            default:
                throw new \Exception("Unsupported database driver: {$config['driver']}");
        }
    }

    /**
     * Restore MySQL database.
     */
    protected function restoreMysql(array $config, string $sqlFile): void
    {
        // Use MYSQL_PWD env var to avoid password warning
        $command = sprintf(
            'MYSQL_PWD=%s mysql --host=%s --port=%s --user=%s %s < %s',
            escapeshellarg($config['password']),
            escapeshellarg($config['host']),
            escapeshellarg($config['port'] ?? 3306),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($sqlFile)
        );

        $this->executeCommand($command);
    }

    /**
     * Restore PostgreSQL database.
     */
    protected function restorePostgres(array $config, string $sqlFile): void
    {
        $command = sprintf(
            'PGPASSWORD=%s psql --host=%s --port=%s --username=%s --dbname=%s < %s',
            escapeshellarg($config['password']),
            escapeshellarg($config['host']),
            escapeshellarg($config['port'] ?? 5432),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($sqlFile)
        );

        $this->executeCommand($command);
    }

    /**
     * Restore SQLite database.
     */
    protected function restoreSqlite(array $config, string $sqlFile): void
    {
        $command = sprintf(
            'sqlite3 %s < %s',
            escapeshellarg($config['database']),
            escapeshellarg($sqlFile)
        );

        $this->executeCommand($command);
    }

    /**
     * Execute a shell command.
     */
    protected function executeCommand(string $command): void
    {
        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes
        );

        if (! is_resource($process)) {
            throw new \Exception('Could not execute restore command.');
        }

        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            // Filter out password warnings
            $filteredErrors = array_filter(
                explode("\n", $errors),
                fn ($line) => ! str_contains($line, 'Using a password')
            );
            $filteredErrors = implode("\n", $filteredErrors);

            if (! empty(trim($filteredErrors))) {
                throw new \Exception("Database restore failed: {$filteredErrors}");
            }
        }
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
            $path = $dir.'/'.$file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }

    /**
     * Format bytes to human readable format.
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
