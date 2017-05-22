<?php

task('web:config', function () {
})->setPrivate();

task('doctrine:fresh_install', function () {
    $cmds = [
        'doctrine:database:drop --force --if-exists --connection=default',
        'doctrine:database:create --connection=default',
        'doctrine:schema:update --force --em=default',
        'doctrine:database:drop --force --if-exists --connection=media',
        'doctrine:database:create --connection=media',
        'doctrine:phpcr:init:dbal --force',
        'doctrine:phpcr:repository:init',
    ];

    foreach ($cmds as $cmd) {
        run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console ' . $cmd . ' --env={{env}} --no-debug --no-interaction');
    }
})->setPrivate();

task('deploy:web', [
    'deploy:init',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'web:config',
    'deploy:build_parameters',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'deploy:vendors',
    'deploy:assetic:dump',
    'deploy:assets_install',
    //'deploy:assets_upload',
    //'deploy:assets_copy', // don't call deploy:init
    'deploy:cache:warmup',
    //'doctrine:fresh_install',
    'cachetool:clear:apcu',
    'deploy:writable',
    'deploy:symlink',
    'cleanup',
])->setPrivate();

after('deploy:web', 'reload:fpm');
after('deploy:web', 'success');

task('deploy:web_update', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'web:config',
    'deploy:build_parameters',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'deploy:vendors',
    'deploy:assetic:dump',
    'deploy:assets_install',
    //'deploy:assets_upload',
    //'deploy:assets_copy',
    'deploy:cache:warmup',
    'database:migrate',
    'cachetool:clear:apcu',
    'deploy:cache:warmup',
    'deploy:writable',
    'deploy:symlink',
    'cleanup',
])->setPrivate();

after('deploy:web_update', 'reload:fpm');
after('deploy:web_update', 'success');

set('copy_dirs', ['vendor', 'web/assets', 'web/bundles']);
set('copy_files', ['app/bootstrap.php.cache']);

task('deploy:web_quick_update', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'web:config',
    'deploy:build_parameters',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'deploy:copy_dirs',
    'deploy:copy_files',
    //'deploy:assets_install',
    'database:migrate',
    'deploy:cache:warmup',
    'cachetool:clear:apcu',
    'deploy:writable',
    'deploy:symlink',
    'cleanup',
])->setPrivate();

after('deploy:web_quick_update', 'reload:fpm');
after('deploy:web_quick_update', 'success');

task('web:deploy', [
    'deploy:web',
])->desc('Deploy web system');

task('web:update', [
    'deploy:web_update',
])->desc('Update web system');

task('web:quick_update', [
    'deploy:web_quick_update',
])->desc('Quick update web system');

task('web:command', function () {
    $cmds = [
        'paysbuy:currency:update',
    ];

    foreach ($cmds as $cmd) {
        run('{{bin/php}} {{deploy_path}}/current/' . trim(get('bin_dir'), '/') . '/console ' . $cmd . ' --env={{env}} --no-debug --no-interaction');
    }
});

after('web:command', 'deploy:writable');
