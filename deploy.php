<?php
namespace Deployer;

require 'recipe/symfony4.php';

$destinationDir = "salvedigital.site/";

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
    ->set('deploy_path', '~/' . $destinationDir . '{{application}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

