<?php

use Illuminate\Support\Str;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Connections
    |--------------------------------------------------------------------------
    */
    'connections' => [
        'source' => env('DB_SYNC_SOURCE', 'production'),
        'target' => env('DB_SYNC_TARGET', 'local'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Database Connection (for remote backup/sync)
    |--------------------------------------------------------------------------
    */
    'production' => [
        'host' => env('DB_SYNC_PRODUCTION_HOST'),
        'port' => env('DB_SYNC_PRODUCTION_PORT', 3306),
        'database' => env('DB_SYNC_PRODUCTION_DATABASE'),
        'username' => env('DB_SYNC_PRODUCTION_USERNAME'),
        'password' => env('DB_SYNC_PRODUCTION_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SSH Tunnel Configuration (for remote database access)
    |--------------------------------------------------------------------------
    */
    'ssh' => [
        'enabled' => env('DB_SYNC_SSH_ENABLED', false),
        'host' => env('DB_SYNC_SSH_HOST'),
        'user' => env('DB_SYNC_SSH_USER'),
        'port' => env('DB_SYNC_SSH_PORT', 22),
        // Comma-separated list of SSH key names to try (in ~/.ssh/)
        'keys' => env('DB_SYNC_SSH_KEYS', 'id_rsa'),
        // Local port for SSH tunnel
        'local_port' => env('DB_SYNC_SSH_LOCAL_PORT', 33066),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync Direction
    |--------------------------------------------------------------------------
    | Options: 'down' (source -> target), 'up' (target -> source), 
    |          'bidirectional' (both ways)
    */
    'direction' => env('DB_SYNC_DIRECTION', 'down'),

    /*
    |--------------------------------------------------------------------------
    | Conflict Resolution Strategy
    |--------------------------------------------------------------------------
    | Options: 'last-write-wins', 'production-wins', 'local-wins', 
    |          'source-wins', 'target-wins', 'manual'
    */
    'conflict_strategy' => env('DB_SYNC_CONFLICT_STRATEGY', 'last-write-wins'),

    /*
    |--------------------------------------------------------------------------
    | Tables Configuration
    |--------------------------------------------------------------------------
    */
    'tables' => [
        // Tables to always exclude
        'exclude' => [
            'migrations',
            'sessions',
            'cache',
            'jobs',
            'failed_jobs',
        ],
        
        // Specific tables to include (empty = all except excluded)
        'include' => [],
        
        // Per-table overrides
        'overrides' => [
            'users' => [
                'conflict_strategy' => 'production-wins',
                'sanitize' => ['password', 'remember_token'],
            ],
            'posts' => [
                'conflict_strategy' => 'last-write-wins',
                'handle_deletes' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync Tracking
    |--------------------------------------------------------------------------
    */
    'track_changes' => true,
    'track_table' => 'db_sync_tracking',

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    */
    'auto_backup' => true,
    'backup_before_sync' => true,
    'backup_retention_days' => 7,
    'backup_path' => storage_path('db-backups'),

    // Full path to mysqldump binary. Set this if PHP can't find mysqldump in its PATH.
    // e.g. '/usr/local/bin/mysqldump' or '/Users/you/Library/Application Support/Herd/bin/mysqldump'
    'mysqldump_path' => env('DB_SYNC_MYSQLDUMP_PATH', 'mysqldump'),

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'chunk_size' => 1000,
    'timeout' => 3600, // seconds

    /*
    |--------------------------------------------------------------------------
    | Scheduling (for real-time/periodic sync)
    |--------------------------------------------------------------------------
    */
    'schedule' => [
        'enabled' => false,
        'frequency' => 'hourly', // hourly, daily, weekly
        'time' => '02:00', // For daily/weekly syncs
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'require_confirmation' => true,
    'allowed_environments' => ['local', 'staging'],

    /*
    |--------------------------------------------------------------------------
    | Sanitization Rules
    |--------------------------------------------------------------------------
    | Define how to sanitize sensitive data when syncing to non-production
    */
    'sanitizers' => [
        'email' => fn($value) => 'user_' . uniqid() . '@example.com',
        'password' => fn() => bcrypt('password'),
        'remember_token' => fn() => null,
        'api_token' => fn() => Str::random(60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    | Jobs are dispatched to the queue named below. Set the connection to
    | 'database' (default), 'redis', or any other configured driver.
    | The worker is managed from the Web UI (/db-sync) or via the CLI.
    |
    | Required: QUEUE_CONNECTION must NOT be 'sync' for background processing.
    | Recommended .env: QUEUE_CONNECTION=database
    */
    'queue' => [
        'connection' => env('DB_SYNC_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'database')),
        'name'       => env('DB_SYNC_QUEUE_NAME', 'db-sync'),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    */
    'ui' => [
        'enabled' => true,
        'route_prefix' => 'db-sync',
        'middleware' => ['web', 'auth'],
        'layout' => 'layouts.db-sync',
    ],
];
