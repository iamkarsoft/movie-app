<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup
                            {--production : Create a production backup}
                            {--local : Create a local backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup with environment prefix (production- or local-)';

    protected ?int $sshTunnelPid = null;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $environment = $this->getEnvironment();

        $this->info("Creating {$environment} database backup...");

        // If production flag and production credentials exist, do remote backup
        if ($environment === 'production' && $this->hasProductionCredentials()) {
            return $this->backupProductionDatabase();
        }

        // Run the spatie backup command for local
        $exitCode = Artisan::call('backup:run', [
            '--only-db' => true,
            '--disable-notifications' => true,
        ], $this->output);

        if ($exitCode !== 0) {
            $this->error('Backup failed.');

            return 1;
        }

        // Rename the backup file with environment prefix
        $this->renameBackupWithPrefix($environment);

        $this->info("Backup completed successfully with prefix: {$environment}-");

        return 0;
    }

    /**
     * Check if production database credentials are configured.
     */
    protected function hasProductionCredentials(): bool
    {
        return ! empty(env('PRODUCTION_DB_HOST'))
            && ! empty(env('PRODUCTION_DB_DATABASE'))
            && ! empty(env('PRODUCTION_DB_USERNAME'));
    }

    /**
     * Backup the production database via SSH tunnel.
     */
    protected function backupProductionDatabase(): int
    {
        $sshHost = env('PRODUCTION_SSH_HOST');
        $useSSH = ! empty($sshHost);
        $localPort = 33066; // Local port for SSH tunnel

        try {
            if ($useSSH) {
                $this->info('Establishing SSH tunnel...');
                $this->createSSHTunnel($localPort);
                sleep(2); // Wait for tunnel to establish
            }

            $this->info('Dumping production database...');
            $sqlFile = $this->dumpProductionDatabase($useSSH ? $localPort : null);

            $this->info('Creating backup archive...');
            $this->createBackupArchive($sqlFile, 'production');

            // Cleanup SQL file
            @unlink($sqlFile);

            $this->info('Backup completed successfully with prefix: production-');

            return 0;
        } catch (\Exception $e) {
            $this->error('Production backup failed: '.$e->getMessage());

            return 1;
        } finally {
            $this->closeSSHTunnel();
        }
    }

    /**
     * Create SSH tunnel to production server.
     */
    protected function createSSHTunnel(int $localPort): void
    {
        $sshHost = env('PRODUCTION_SSH_HOST');
        $sshUser = env('PRODUCTION_SSH_USER');
        $sshKeyPath = $this->resolveSSHKeyPath();
        $sshPort = env('PRODUCTION_SSH_PORT', 22);
        $dbHost = env('PRODUCTION_DB_HOST', '127.0.0.1');
        $dbPort = env('PRODUCTION_DB_PORT', 3306);

        if (! $sshKeyPath) {
            throw new \Exception('No SSH key found. Set PRODUCTION_SSH_KEYS in your .env file.');
        }

        $command = sprintf(
            'ssh -f -N -L %d:%s:%d -i %s -p %d -o StrictHostKeyChecking=no -o ExitOnForwardFailure=yes %s@%s',
            $localPort,
            $dbHost,
            $dbPort,
            escapeshellarg($sshKeyPath),
            $sshPort,
            escapeshellarg($sshUser),
            escapeshellarg($sshHost)
        );

        exec($command.' 2>&1', $output, $exitCode);

        if ($exitCode !== 0) {
            throw new \Exception('Failed to create SSH tunnel: '.implode("\n", $output));
        }

        // Find the SSH tunnel PID
        exec("pgrep -f 'ssh.*{$localPort}.*{$sshHost}'", $pidOutput);
        $this->sshTunnelPid = ! empty($pidOutput) ? (int) $pidOutput[0] : null;

        $this->line('SSH tunnel established on port '.$localPort);
    }

    /**
     * Resolve the SSH key path with fallback.
     */
    protected function resolveSSHKeyPath(): ?string
    {
        $home = $_SERVER['HOME'] ?? getenv('HOME');
        $sshDir = $home.'/.ssh';

        // Get key names from env (comma-separated)
        $keyNames = env('PRODUCTION_SSH_KEYS', 'id_rsa');
        $keys = array_map('trim', explode(',', $keyNames));

        foreach ($keys as $keyName) {
            $keyPath = $sshDir.'/'.$keyName;
            if (file_exists($keyPath)) {
                $this->line("Using SSH key: {$keyName}");

                return $keyPath;
            }
        }

        return null;
    }

    /**
     * Close the SSH tunnel.
     */
    protected function closeSSHTunnel(): void
    {
        if ($this->sshTunnelPid) {
            exec("kill {$this->sshTunnelPid} 2>/dev/null");
            $this->line('SSH tunnel closed.');
            $this->sshTunnelPid = null;
        }
    }

    /**
     * Dump the production database.
     */
    protected function dumpProductionDatabase(?int $tunnelPort): string
    {
        $host = $tunnelPort ? '127.0.0.1' : env('PRODUCTION_DB_HOST');
        $port = $tunnelPort ?? env('PRODUCTION_DB_PORT', 3306);
        $database = env('PRODUCTION_DB_DATABASE');
        $username = env('PRODUCTION_DB_USERNAME');
        $password = env('PRODUCTION_DB_PASSWORD', '');

        $tempDir = storage_path('app/backup-temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $sqlFile = $tempDir.'/'.$database.'.sql';

        // Use MYSQL_PWD env var to avoid password warning
        $envPrefix = sprintf('MYSQL_PWD=%s ', escapeshellarg($password));

        $command = sprintf(
            '%smysqldump --host=%s --port=%d --user=%s --single-transaction --routines --triggers %s 2>/dev/null',
            $envPrefix,
            escapeshellarg($host),
            $port,
            escapeshellarg($username),
            escapeshellarg($database)
        );

        $sqlContent = shell_exec($command);

        if (empty($sqlContent)) {
            throw new \Exception('Database dump failed or returned empty.');
        }

        file_put_contents($sqlFile, $sqlContent);

        $this->line('Database dumped: '.$this->formatBytes(filesize($sqlFile)));

        return $sqlFile;
    }

    /**
     * Create backup archive from SQL file.
     */
    protected function createBackupArchive(string $sqlFile, string $environment): void
    {
        $disk = Storage::disk('backups');
        $appName = config('app.name');
        $timestamp = date('Y-m-d-H-i-s');
        $filename = "{$environment}-{$timestamp}.zip";

        $backupDir = $appName;
        if (! $disk->exists($backupDir)) {
            $disk->makeDirectory($backupDir);
        }

        $zipPath = storage_path("app/backups/{$backupDir}/{$filename}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new \Exception('Could not create zip archive.');
        }

        $zip->addFile($sqlFile, 'db-dumps/'.basename($sqlFile));
        $zip->close();

        $this->line("Created: {$filename}");
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
     * Rename the most recent backup with environment prefix.
     */
    protected function renameBackupWithPrefix(string $environment): void
    {
        $disk = Storage::disk('backups');
        $appName = config('app.name');

        $files = $disk->allFiles($appName);
        $backups = array_filter($files, fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'zip');

        if (empty($backups)) {
            return;
        }

        // Get the most recent backup (by modification time)
        usort($backups, fn ($a, $b) => $disk->lastModified($b) - $disk->lastModified($a));
        $latestBackup = $backups[0];

        $filename = basename($latestBackup);

        // Skip if already has an environment prefix
        if (str_starts_with($filename, 'production-') || str_starts_with($filename, 'local-')) {
            return;
        }

        $newFilename = $environment.'-'.$filename;
        $newPath = dirname($latestBackup).'/'.$newFilename;

        // Rename the file
        $disk->move($latestBackup, $newPath);

        $this->line("Renamed: {$filename} → {$newFilename}");
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
