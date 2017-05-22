<?php

task('web:install:init', function () {
    upload("./deploy/vcare", "{{deploy_path}}/.deploy");
    upload("./deploy/nginx", "{{deploy_path}}/.deploy/nginx");

    run("ln -nfs {{deploy_path}}/.deploy/nginx/default.conf /etc/nginx/sites-enabled/default");
    run("ln -nfs {{deploy_path}}/.deploy/cli/php.ini /etc/php/7.0/cli/conf.d/10-custom.ini");
    run("ln -nfs {{deploy_path}}/.deploy/fpm/php.ini /etc/php/7.0/fpm/conf.d/10-custom.ini");
    run("ln -nfs {{deploy_path}}/.deploy/fpm/pool/www.conf /etc/php/7.0/fpm/pool.d/www.conf");
    run("ln -nfs {{deploy_path}}/.deploy/supervisor/supervisord.conf /etc/supervisor/supervisord.conf");

    // cannot use symlink for mysql due to permission on my.cnf denide by mysql user
    run("cp -f {{deploy_path}}/.deploy/mysql/my.cnf /etc/mysql/my.cnf");

    run("cp -f {{deploy_path}}/.deploy/nginx/nginx.conf /etc/nginx/nginx.conf");
    run("cp -f {{deploy_path}}/.deploy/nginx/conf.d/blacklist.conf /etc/nginx/conf.d/blacklist.conf");
    run("cp -f {{deploy_path}}/.deploy/nginx/conf.d/blockips.conf /etc/nginx/conf.d/blockips.conf");

})->setPrivate();

task('web:install:testing', function () {
    # to testing
    run("rm -rf {{deploy_path}}/current && mkdir -p {{deploy_path}}/current/web");
    run("ln -nfs {{deploy_path}}/.deploy/app.php {{deploy_path}}/current/web/app.php");

    # github connect test
    //run('ssh -yvT git@github.com');
})->setPrivate();

task('web:install:clear', function () {
    run("rm -rf {{deploy_path}}/*");
})->setPrivate();

task('web:install:config', function () {
    run(sprintf(
        "sed -i 's/redis_locale_password/%s/' {{deploy_path}}/.deploy/redis/redis.conf",
        env('config.redis_password')
    ));

    run(sprintf(
        "sed -i 's/supervisor_password/%s/' {{deploy_path}}/.deploy/supervisor/supervisord.conf",
        env('config.supervisor_password')
    ));
})->setPrivate();

task('web:install', [
    'web:install:clear',
    'web:install:init',
    'web:install:config',
    'web:install:testing',
    'reload:fpm',
    'reload:nginx',
    'reload:mysql',
    'reload:supervisor',
])->desc('Init web system');

task('web:reset', [
    'web:install:init',
    'web:install:config',
    'reload:fpm',
    'reload:nginx',
    'reload:mysql',
    'reload:supervisor',
])->desc('Init web system');
