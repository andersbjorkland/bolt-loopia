<?php
namespace Deployer;

require 'recipe/symfony4.php';

set('public_dir', '/www/');
set('project_dir', '~/bolt');

// Project name
set('application', 'bolt');

// Project repository
set('repository', 'https://github.com/andersbjorkland/bolt-loopia');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('satius')
    ->set('http_user', 'satius.digital')
    ->set('deploy_path', '{{project_dir}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// Run after first deployment to add public content to public directory via symlink.
task('symlink:public', function() {
    run('ln -s {{release_path}}/public/*  {{public_dir}} && ln -s {{release_path}}/public/.[^.]* {{public_dir}}');
});

// If symlink doesn't work. Make sure to configure index.php accordingly.
task('copy:public', function() {
    run('cp -R {{release_path}}/public/*  /www && cp -R {{release_path}}/public/.[^.]* /www');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:unlock', 'copy:public');


// Migrate database before symlink new release.

//before('deploy:symlink', 'database:migrate');

