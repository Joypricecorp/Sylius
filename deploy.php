<?php

/*

web-01
host: 188.166.212.14 | 10.130.46.215
redis password: ahboocho0eeMing7Oogh0roZ
supervisor password: ahC5teiXenge3eeng8ya6vei

web-02
host: 128.199.200.235 | 10.130.43.146
redis password: riupahKoh4eeXeiWaengaiy4
supervisor password: Lo0Thoo5ao9ohd5aa9Iecooz

admin
host: 128.199.209.89 | 10.130.34.236
mysql root password: wieLofie7oobooBaing0ohng
redis password: tahweihoan8ai3foo2Seedo1
supervisor password: too7faenai1Ahch3tee8oeko

 */

require 'deploy/base.php';
require 'deploy/vcare/install.php';
require 'deploy/vcare/deploy.php';
require 'deploy/vcare/install.php';
require 'deploy/vcare/deploy.php';

env('timezone', 'Asia/Bangkok');

serverList('deploy/vcare/servers.yml');

set('repository', 'https://github.com/Joypricecorp/Sylius.git');
