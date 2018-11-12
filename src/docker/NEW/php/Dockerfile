FROM php:5.6.38-fpm-jessie as devel

RUN buildDeps=" \
    gcc g++ libxslt1-dev libxml2-dev libmcrypt-dev libtidy-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev libbz2-dev zlib1g-dev libicu-dev \
    libpcre3-dev git \
    " \
    && enableExtensions=" \
    redis apcu phalcon\
    " \
    && installExtensions=" \
    pdo_mysql mysql mysqli dba bcmath bz2 calendar \
    exif gettext gd intl mcrypt tidy \
    xsl xmlrpc pcntl shmop soap sockets \
    sysvmsg sysvsem sysvshm wddx zip \
    " \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y $buildDeps --no-install-recommends \
    && docker-php-ext-install -j$(nproc) $installExtensions \
    && pecl install redis-4.1.1 \
    && pecl install apcu-4.0.11 \
    && git clone https://github.com/phalcon/cphalcon.git /tmp/phalcon --branch=phalcon-v2.0.9 \
    && cd /tmp/phalcon/build/; ./install \
    && docker-php-ext-enable $enableExtensions \
    && apt-get install -y pwgen \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get purge -y --auto-remove git \
    && rm -rf /tmp/*

# override default WORKDIR
WORKDIR /var/www

# set php.ini
COPY php-devel/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY php-devel/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

################################################################################

FROM php:5.6.38-jessie as cron

RUN buildDeps=" \
    gcc g++ libxslt1-dev libxml2-dev libmcrypt-dev libtidy-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev libbz2-dev zlib1g-dev libicu-dev \
    libpcre3-dev git \
    " \
    && enableExtensions=" \
    redis apcu phalcon\
    " \
    && installExtensions=" \
    pdo_mysql mysql mysqli dba bcmath bz2 calendar \
    exif gettext gd intl mcrypt tidy \
    xsl xmlrpc pcntl shmop soap sockets \
    sysvmsg sysvsem sysvshm wddx zip \
    " \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y $buildDeps --no-install-recommends \
    && docker-php-ext-install -j$(nproc) $installExtensions \
    && pecl install redis-4.1.1 \
    && pecl install apcu-4.0.11 \
    && git clone https://github.com/phalcon/cphalcon.git /tmp/phalcon --branch=phalcon-v2.0.9 \
    && cd /tmp/phalcon/build/; ./install \
    && docker-php-ext-enable $enableExtensions \
    && apt-get install -y pwgen \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get purge -y --auto-remove git \
    && rm -rf /tmp/*

# set php.ini
COPY cron/php.ini $PHP_INI_DIR/php.ini

COPY cron/run-cron.sh /run-cron.sh
COPY cron/euromillions.crons /etc/cron.d/euromillions.crons

RUN apt-get update && apt-get install -y cron \
    && chmod -v +x /run-cron.sh \
    && crontab /etc/cron.d/euromillions.crons

# override default WORKDIR
WORKDIR /var/www

CMD ["/run-cron.sh"]

################################################################################

FROM php:5.6.38-jessie as tools

RUN buildDeps=" \
    gcc g++ libxslt1-dev libxml2-dev libmcrypt-dev libtidy-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev libbz2-dev zlib1g-dev libicu-dev \
    libpcre3-dev git \
    " \
    && enableExtensions=" \
    redis apcu phalcon\
    " \
    && installExtensions=" \
    pdo_mysql mysql mysqli dba bcmath bz2 calendar \
    exif gettext gd intl mcrypt tidy \
    xsl xmlrpc pcntl shmop soap sockets \
    sysvmsg sysvsem sysvshm wddx zip \
    " \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y $buildDeps --no-install-recommends \
    && docker-php-ext-install -j$(nproc) $installExtensions \
    && pecl install redis-4.1.1 \
    && pecl install apcu-4.0.11 \
    && git clone https://github.com/phalcon/cphalcon.git /tmp/phalcon --branch=phalcon-v2.0.9 \
    && cd /tmp/phalcon/build/; ./install \
    && docker-php-ext-enable $enableExtensions \
    && apt-get install -y pwgen \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get purge -y --auto-remove git \
    && rm -rf /tmp/*

# set php.ini
COPY tools/php.ini $PHP_INI_DIR/php.ini

# composer
RUN apt-get update \
    && apt-get install -y git zip unzip \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# override default WORKDIR
WORKDIR /var/www

ENV user deploybot

RUN useradd -m -d /home/${user} ${user} \
 && chown -R ${user} /home/${user}

USER ${user}

################################################################################

FROM php:5.6.38-fpm-jessie as staging

RUN buildDeps=" \
    gcc g++ libxslt1-dev libxml2-dev libmcrypt-dev libtidy-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev libbz2-dev zlib1g-dev libicu-dev \
    libpcre3-dev git \
    " \
    && enableExtensions=" \
    redis apcu phalcon\
    " \
    && installExtensions=" \
    pdo_mysql mysql mysqli dba bcmath bz2 calendar \
    exif gettext gd intl mcrypt tidy \
    xsl xmlrpc pcntl shmop soap sockets \
    sysvmsg sysvsem sysvshm wddx zip \
    " \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y $buildDeps --no-install-recommends \
    && docker-php-ext-install -j$(nproc) $installExtensions \
    && pecl install redis-4.1.1 \
    && pecl install apcu-4.0.11 \
    && git clone https://github.com/phalcon/cphalcon.git /tmp/phalcon --branch=phalcon-v2.0.9 \
    && cd /tmp/phalcon/build/; ./install \
    && docker-php-ext-enable $enableExtensions \
    && apt-get install -y pwgen \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get purge -y --auto-remove git \
    && rm -rf /tmp/*

# override default WORKDIR
WORKDIR /var/www

# set php.ini
COPY php-staging/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY php-staging/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

################################################################################

FROM php:5.6.38-fpm-jessie as production

################################################################################
