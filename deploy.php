<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', '$APPLICATION_NAME$');

// Project repository
set('repository', '$GIT$');

// Folder names in releases.
set('release_name', function () {
    return date('YmdHis') . '_' . exec('git show -s --format=%h');
});

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Set the branch that should be used for pulling.
set('branch', 'master');

// Set multiplexing.
set('ssh_multiplexing', false);

// Set amount of releases to keep.
set('keep_releases', 5);

// Dev environment host.
host('$HOST$')
    ->stage('dev')
    ->user('$USER$')
    ->identityFile('~/.ssh/$PRIVATE_KEY$')
    ->forwardAgent(true)
    ->port(22)
    ->set('env', [
        //Env variables.
    ])
    ->set('deploy_path', '~/{{application}}/dev');

// Custom Tasks.
task('build', function () {
    run('cd {{release_path}} && build');
});

// Deploy task.
desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    //'artisan:storage:link',
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:optimize',
    'artisan:migrate',
    'artisan:db:seed:production',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);


desc('Refresh the project');
task('refresh:project', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
    'artisan:optimize',
    'storage:clear',
    'artisan:migrate:fresh',
    'artisan:db:seed:production',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

// If deployment fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Custom tasks
desc('Run the production seeders');
task('artisan:db:seed:production', function () {
    run('{{bin/php}} {{release_path}}/artisan db:seed --class=ProductionDatabaseSeeder');
});
