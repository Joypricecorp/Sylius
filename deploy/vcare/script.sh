#!/usr/bin/env bash

# https://zach-adams.com/2015/01/apt-get-cant-connect-to-security-ubuntu-fix/
sudo bash -c 'echo "precedence ::ffff:0:0/96 100" >> /etc/gai.conf'

timedatectl set-timezone Asia/Bangkok

bash -c 'echo "LC_CTYPE=en_US.UTF-8" >> /etc/default/locale'
bash -c 'echo LC_ALL=en_US.UTF-8 >> /etc/default/locale'

# Keep upstart from complaining
dpkg-divert --local --rename --add /sbin/initctl
ln -sf /bin/true /sbin/initctl

export DEBIAN_FRONTEND=noninteractive

apt-get -y update
apt-get -y upgrade

# redis
apt-get -y install build-essential tcl
cd /tmp && curl -O http://download.redis.io/redis-stable.tar.gz && tar xzvf redis-stable.tar.gz
cd redis-stable
make && make install
mkdir /etc/redis && mkdir /var/lib/redis

apt-get -y install rabbitmq-server

# basic requirements
apt-get -y install \
    curl \
    git \
    unzip \
    nodejs \
    npm \
    ruby \
    gem \
    ufw \
    htop \
    sendmail \
    mysql-server \
    mysql-client \
    nginx \
    php7.0-dev \
    php-fpm \
    php-mysql \
    php-apcu \
    pwgen \
    supervisor

npm install -g gulp
sudo su -c "gem install sass"
ln -s /usr/bin/nodejs /usr/bin/node

# sf requirements
apt-get -y install \
    php-curl \
    php-gd \
    php-intl \
    php-pear \
    php-imagick \
    php-imap \
    php-mcrypt \
    php-memcache \
    php-mbstring \
    php-pspell \
    php-recode \
    php7.0-sqlite3 \
    php-tidy \
    php-xmlrpc \
    php-zip \
    php-redis \
    php-xml \
    php-amqp

#pecl install amqp

# bugfix: https://github.com/Supervisor/supervisor/issues/735#issuecomment-219364268
sudo systemctl enable supervisor.service

# security
sed -i 's/Port 22/Port 25252/' /etc/ssh/sshd_config

ufw allow 25252     # ssh
ufw allow 80        # web
ufw allow 443       # web https
ufw allow 1234      # redis
ufw allow 4567      # supervisor
ufw allow 7890      # mysql
ufw default deny incoming
ufw enable

mkdir -p /var/www
chown -R www-data:www-data /var/www

# github test
ssh -yvT git@github.com

# mysql setup
MYSQL_PASSWORD=`pwgen -c -n -1 24`

mysqladmin -u root password $MYSQL_PASSWORD
mysql -uroot -p$MYSQL_PASSWORD -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '$MYSQL_PASSWORD' WITH GRANT OPTION; FLUSH PRIVILEGES;DROP USER 'root'@'localhost';"

#This is so the passwords show up in logs.
echo mysql root password: $MYSQL_PASSWORD

# helper
REDIS_PASSWORD=`pwgen -c -n -1 24`
SUPERVISOR_PASSWORD=`pwgen -c -n -1 24`

echo redis password: $REDIS_PASSWORD
echo supervisor password: $SUPERVISOR_PASSWORD

shutdown -r now

# ngxtop
apt-get -y install python-pip
pip install --upgrade pip
pip install ngxtop
