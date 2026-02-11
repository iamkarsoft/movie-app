# Database Backup & Restore

Database backup and restore functionality using Spatie Laravel Backup with environment prefixes.

## Backup Naming Convention

Backups are named with environment prefixes:
- `production-*.zip` - Production database backups
- `local-*.zip` - Local/development database backups

## Commands

### Create a Backup

```bash
# Backup current environment (based on APP_ENV)
php artisan db:backup

# Explicitly backup as production
php artisan db:backup --production

# Explicitly backup as local
php artisan db:backup --local
```

### Restore a Backup

```bash
# Restore from current environment backups (interactive selection)
php artisan db:restore

# Restore from production backups
php artisan db:restore --production

# Restore from local backups
php artisan db:restore --local

# Restore a specific backup file
php artisan db:restore --production --backup=production-2024-01-15-10-30-00.zip

# Skip confirmation prompt
php artisan db:restore --production --force
```

### Sync Database (Merge Mode)

Sync merges records from a backup without deleting existing data:
- **Updates** existing records (matched by primary key)
- **Creates** new records that don't exist
- **Never deletes** records

```bash
# Sync from current environment's latest backup
php artisan db:sync

# Sync from latest production backup
php artisan db:sync --production

# Sync from latest local backup
php artisan db:sync --local

# Skip confirmation prompt
php artisan db:sync --production --force
```

**Note:** Sync excludes system tables (migrations, password_resets, etc.).

### List & Manage Backups

```bash
# List all backups
php artisan backup:list

# Clean old backups (per retention policy in config/backup.php)
php artisan backup:clean
```

## Configuration

Backup settings are in `config/backup.php`:
- Retention policy (how long to keep backups)
- Compression settings
- Notification settings

## Production Database Setup

To backup from a remote production database, add these to your `.env`:

```env
# Production Database Credentials
PRODUCTION_DB_HOST=127.0.0.1
PRODUCTION_DB_PORT=3306
PRODUCTION_DB_DATABASE=your_production_db
PRODUCTION_DB_USERNAME=db_user
PRODUCTION_DB_PASSWORD=db_password

# SSH Tunnel (if database is not directly accessible)
PRODUCTION_SSH_HOST=your-server.com
PRODUCTION_SSH_USER=deploy
PRODUCTION_SSH_PORT=22

# SSH key names to try (comma-separated, looks in ~/.ssh/)
PRODUCTION_SSH_KEYS=my_key,backup_key
```

### How it works:
- If `PRODUCTION_SSH_HOST` is set, an SSH tunnel is created to access the database
- If not set, it connects directly to `PRODUCTION_DB_HOST`
- `PRODUCTION_SSH_KEYS` is a comma-separated list of SSH key names (not paths)
- Keys are tried in order from `~/.ssh/` until one is found

## Storage Location

Backups are stored in: `storage/app/backups/{APP_NAME}/`

## Requirements

- MySQL: `mysql` and `mysqldump` CLI tools
- PostgreSQL: `psql` and `pg_dump` CLI tools
- SQLite: `sqlite3` CLI tool
- SSH client (for remote production backups)
