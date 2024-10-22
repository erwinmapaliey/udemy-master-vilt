<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config
set('ssh_multiplexing', true);
set('branch', 'master');
set('git_tty', false);
set('bin/php', '/usr/bin/php8.3');
set('application', 'master-vilt');
set('repository', 'https://github.com/erwinmapaliey/udemy-master-vilt.git');

add('shared_files', ['.env']);
add('shared_dirs', ['public/files', 'storage']);
add('shared_dirs', ['public/images', 'storage']);

add('writable_dirs', []);

set('keep_releases', 5);

// Hosts
host('production')
    ->setHostname('82.180.137.211')
    ->set('forward_agent',false)
    ->set('remote_user', 'deploy')
    ->set('port', 2210)
    ->set('deploy_path', '/var/www/{{application}}')
    ->setLabels([
        'type' => 'app',
        'env' => 'production',
    ]);

// Tasks
task('deploy:vendors', function () {
    run('cd {{release_path}} && {{bin/php}} /usr/local/bin/composer install --no-dev --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
});

task('artisan:clear-compiled', function () {
    run('{{bin/php}} {{release_path}}/artisan clear-compiled');
});

task('restart:web', function () {
    run('sudo service php8.3-fpm restart');
    run('sudo service nginx restart');
})->select('type=app');

task('restart:workers', function () {
    run('{{bin/php}} {{release_path}}/artisan queue:restart');
})->select('type=app');

task('restart:services', ['restart:web', 'restart:workers']);

// Hooks
after('deploy:failed', 'deploy:unlock');

before('artisan:config:cache', 'artisan:clear-compiled');
before('deploy:success', 'restart:services');