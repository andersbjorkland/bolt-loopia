<?php
namespace Deployer;

require 'recipe/symfony4.php';

set('public_dir', '~/salvedigital.site/public_html/');
set('project_dir', '~/salvedigital.site/bolt');

// Project name
set('application', 'bolt');

// Project repository
set('repository', 'https://github.com/andersbjorkland/bolt-loopia');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('salvesite')
    ->set('deploy_path', '{{project_dir}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('symlink:public', function() {
    run('ln -s {{release_path}}/public/*  {{public_dir}} && ln -s {{release_path}}/public/.[^.]* {{public_dir}}');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy:unlock', 'symlink:public');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

