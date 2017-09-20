<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', '$APPLICATION_NAME$');

// Project repository
set('repository', '$GIT_REPO$');

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

// Dev environment host.
host('$HOST$')
    ->stage('dev')
    ->user('$USER$')
    ->identityFile('~/.ssh/$PRIVATE_KEY$')
    ->forwardAgent(true)
    ->port(22)
    ->set('deploy_path', '~/{{application}}/dev');

// Staging environment host.
host('$HOST$')
    ->stage('staging')
    ->user('$USER$')
    ->identityFile('~/.ssh/$PRIVATE_KEY$')
    ->forwardAgent(true)
    ->port(22)
    ->set('deploy_path', '~/{{application}}/staging');

// Live environment host.
host('$HOST$')
    ->stage('live')
    ->user('$USER$')
    ->identityFile('~/.ssh/$PRIVATE_KEY$')
    ->forwardAgent(true)
    ->port(22)
    ->set('deploy_path', '~/{{application}}/live');

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
    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:optimize',
    'artisan:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

// If deployment fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

