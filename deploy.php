<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Movie App');

// Project repository
set('repository', 'https://github.com/iamkarsoft/movie-app');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('167.71.17.50')
    ->set('remote_user','root')
    ->set('deploy_path', '/var/www/movieapp.kofi.work/public_html');
    
// Tasks

task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:migrate'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

