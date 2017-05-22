<?php

require 'recipe/common.php';
require 'vendor/deployphp/recipes/recipes/configure.php';

/**
 * Symfony 3 Configuration
 */

// Symfony shared dirs
set('shared_dirs', ['var/logs', 'var/sessions', 'web/media']);

// Symfony writable dirs
set('writable_dirs', ['var/cache', 'var/logs', 'var/sessions', 'web/media']);

// Symfony executable and variable directories
set('bin_dir', 'bin');
set('var_dir', 'var');

// Symfony shared files
set('shared_files', ['app/config/parameters.yml']);

// Assets
set('assets', ['web/assets', 'web/bundles']);

// Default true - BC for Symfony < 3.0
set('dump_assets', true);

set('writable_use_sudo', false);

// Environment vars
env('env_vars', 'SYMFONY_ENV=prod');
env('env', 'prod');

set('cachetool', '/run/php/php7.0-fpm.sock');

/**
 * Clear apc cache
 */
task('cachetool:clear:apcu', function () {
    $releasePath = env('release_path');
    $env = env();
    $options = $env->has('cachetool') ? $env->get('cachetool') : get('cachetool');

    if (strlen($options)) {
        $options = "--fcgi={$options}";
    }

    cd($releasePath);
    $hasCachetool = run("if [ -e $releasePath/cachetool.phar ]; then echo 'true'; fi");

    if ('true' !== $hasCachetool) {
        run("curl -sO http://gordalina.github.io/cachetool/downloads/cachetool.phar");
    }

    run("{{bin/php}} cachetool.phar apcu:cache:clear {$options}");
})->desc('Clearing APCu system cache');

/**
 * Create cache dir
 */
task('deploy:create_cache_dir', function () {
    // Set cache dir
    env('cache_dir', '{{release_path}}/' . trim(get('var_dir'), '/') . '/cache');

    // Remove cache dir if it exist
    run('if [ -d "{{cache_dir}}" ]; then rm -rf {{cache_dir}}; fi');

    // Create cache dir
    run('mkdir -p {{cache_dir}}');

    // Set rights
    run("chmod -R g+w {{cache_dir}}");
})->desc('Create cache dir');


/**
 * Normalize asset timestamps
 */
task('deploy:assets', function () {
    $assets = implode(' ', array_map(function ($asset) {
        return "{{release_path}}/$asset";
    }, get('assets')));

    $time = date('Ymdhi.s');

    run("find $assets -exec touch -t $time {} ';' &> /dev/null || true");
})->desc('Normalize asset timestamps');


/**
 * Dump all assets to the filesystem
 */
task('deploy:assetic:dump', function () {
    if (!get('dump_assets')) {
        return;
    }

    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console assetic:dump --env={{env}} --no-debug');

})->desc('Dump assets');


/**
 * Warm up cache
 */
task('deploy:cache:warmup', function () {

    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console cache:warmup  --env={{env}} --no-debug');

})->desc('Warm up cache');


/**
 * Migrate database
 */
task('database:migrate', function () {

    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console doctrine:migrations:migrate --env={{env}} --no-debug --no-interaction --allow-no-migration');

})->desc('Migrate database');


/**
 * Remove app_dev.php files
 */
task('deploy:clear_controllers', function () {

    run("rm -f {{release_path}}/web/app_*.php");
    run("rm -f {{release_path}}/web/config.php");

})->setPrivate();

/**
 * Copy directories. Useful for vendors directories
 */
task('deploy:copy_files', function () {

    $files = get('copy_files');

    foreach ($files as $file) {
        // Delete file if exists.
        run("if [ -f $(echo {{release_path}}/$file) ]; then rm -rf {{release_path}}/$file; fi");

        // Copy file.
        run("if [ -f $(echo {{deploy_path}}/current/$file) ]; then cp -rpf {{deploy_path}}/current/$file {{release_path}}/$file; fi");
    }

})->desc('Copy files')->setPrivate();

after('deploy:update_code', 'deploy:clear_controllers');

task('deploy:init', function () {
    run("rm -rf {{deploy_path}}/current");
})->setPrivate();

task('redis:flushall', function () {
    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console redis:flushall --env={{env}} --no-debug --no-interaction');
});

task('deploy:sylius_theme', function () {
    run("rm -rf {{release_path}}/web/bundles/_themes");
    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console sylius:theme:assets:install {{release_path}}/web --symlink --env={{env}} --no-debug');
})->setPrivate();

task('deploy:assets_upload', function () {
    foreach (get('assets') as $asset) {
        upload("./" . $asset, "{{release_path}}/" . $asset);
    }
});

task('deploy:assets_copy', function () {
    foreach (get('assets') as $asset) {
        run(sprintf("cp -rf {{deploy_path}}/current/%s/* {{release_path}}/%s", $asset, $asset));
    }
})->setPrivate();

task('deploy:assets_install', function () {
    run('cd {{release_path}} && npm install && npm run gulp');
    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console sylius:theme:assets:install {{release_path}}/web --env={{env}} --no-debug');
});

task('npm:install', function () {
    run('rm -rf /usr/local/lib/node_modules && npm install -g npm@latest');
    run('npm install -g npm-install-version');
    run('npm install -g gulp');
    run('npm cache clean && npm -v');
});

task('npm:install-minimatch', function () {
    run('rm -rf /usr/local/lib/node_modules/minimatch');
    run('npm install -g minimatch@latest && npm -v minimatch');
});

task('doctrine:cache_clear', function () {
    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console redis:flushdb --client=cache --env={{env}} --no-debug --no-interaction');
});

/**
 * Main task
 */
task('deploy', [/* nothing to do, split tasks (admin, web) to deploy */])->desc('Deploy your project');

task('test', function() {
    runLocally('ssh-add -l');
    run('ssh-add -l');
    //run('ssh -yvT git@github.com');
});

task('reload:fpm', function () {
    run('systemctl restart php7.0-fpm');
});

task('reload:nginx', function () {
    run('nginx -t');
    run('systemctl restart nginx');
});

task('reload:supervisor', function () {
    run('systemctl restart supervisor');
});

task('reload:mysql', function () {
    run('systemctl restart mysql');
});

task('deploy:build_parameters', function () {
    $baseParameters = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(
        env('config.base_parameters')
    ));

    $parameters = array_replace_recursive(
        $baseParameters, ['parameters' => env('parameters')]
    );

    $newParameters = \Symfony\Component\Yaml\Yaml::dump($parameters);

    run("mkdir -p {{deploy_path}}/shared/app/config");
    run('echo "'. $newParameters .'" > {{deploy_path}}/shared/app/config/parameters.yml');

});

after('rollback', 'reload:fpm');
