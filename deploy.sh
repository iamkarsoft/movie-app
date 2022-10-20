#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    git fetch origin master
    git reset --hard origin/master
    git pull origin master

    # Install dependencies based on lock file
    # composer install --no-interaction --prefer-dist --optimize-autoloader
    composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    # Migrate database
    php artisan migrate

    # Note: If you're using queue workers, this is the place to restart them.
    # ...

    # Clear cache
    # php artisan optimize

    # Reload PHP to update opcache
    #echo "" | sudo -S service php7.4-fpm reload
# Exit maintenance mode
php artisan up

echo "Application deployed!"
