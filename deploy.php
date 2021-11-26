<?php
namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'symfony-test');

// Project repository
set('repository', 'https://github.com/Dimashaman/symfony-test.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);
set('ssh_multiplexing', false);
set('branch', 'work_on_errors');
// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts

host('176.99.11.239')
    ->set('deploy_path', '/var/www/deployment')
    ->user('deploy');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php8.0-fpm reload');
});

after('deploy', 'reload:php-fpm');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');
