ARG PHP_VERSION=5.6.38

FROM php:${PHP_VERSION}-fpm-jessie as base
#FROM panamedialottery/euromillions-base-php as base

ARG PHALCON_BRANCH=phalcon-v2.0.9

RUN buildDeps=" \
    gcc g++ libxslt1-dev libxml2-dev libmcrypt-dev libtidy-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev libbz2-dev zlib1g-dev libicu-dev \
    libpcre3-dev git \
    " \
    && enableExtensions=" \
    spl_types redis apcu phalcon\
    " \
    && installExtensions=" \
    pdo_mysql mysql mysqli dba bcmath bz2 calendar \
    exif gettext gd intl mcrypt tidy \
    xsl xmlrpc pcntl shmop soap sockets \
    sysvmsg sysvsem sysvshm wddx zip opcache \
    " \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y $buildDeps --no-install-recommends \
    && docker-php-ext-install -j$(nproc) $installExtensions \
    && pecl install redis-4.1.1 \
    && pecl install apcu-4.0.11 \
    && pecl install SPL_Types \
    && git clone https://github.com/phalcon/cphalcon.git /tmp/phalcon --branch=${PHALCON_BRANCH} \
    && cd /tmp/phalcon/build/; ./install safe \
    && docker-php-ext-enable $enableExtensions \
    && apt-get install -y pwgen \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get purge -y --auto-remove git \
    && rm -rf /tmp/*

# override default WORKDIR
WORKDIR /var/www

################################################################################

FROM base as tools

# set php.ini
COPY devOps/docker/php/tools/php.ini $PHP_INI_DIR/php.ini

# composer
RUN apt-get update \
    && apt-get install -y git zip unzip \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV user deploybot

RUN useradd -m -d /home/${user} ${user} \
 && chown -R ${user} /home/${user}

USER ${user}

################################################################################

FROM base as common-tasks-onbuild

COPY --chown=www-data:www-data ./src .
#RUN chown -R www-data:www-data /var/www

VOLUME /var/www

################################################################################

FROM common-tasks-onbuild as cron

# set php.ini
COPY devOps/docker/php/cron/php.ini $PHP_INI_DIR/php.ini

COPY devOps/docker/php/cron/entrypoint.sh /entrypoint.sh
COPY devOps/docker/php/cron/euromillions.crons /etc/cron.d/euromillions.crons

RUN apt-get update && apt-get install -y cron supervisor \
    && chmod -v +x /entrypoint.sh \
    && crontab /etc/cron.d/euromillions.crons \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*

COPY devOps/docker/php/cron/supervisord.conf /etc/supervisor/supervisord.conf
COPY devOps/docker/php/cron/supervisor/services.conf /etc/supervisor/conf.d/services.conf

CMD ["/entrypoint.sh"]

################################################################################

FROM common-tasks-onbuild as prod

# set php.ini
COPY devOps/docker/php/php-prod/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY devOps/docker/php/php-prod/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

# set post-deploy script
COPY devOps/docker/php/php-prod/post-deploy.sh /post-deploy.sh
RUN chmod 700 /post-deploy.sh

################################################################################

FROM common-tasks-onbuild as staging

# set php.ini
COPY devOps/docker/php/php-staging/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY devOps/docker/php/php-staging/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

# set post-deploy script
COPY devOps/docker/php/php-staging/post-deploy.sh /post-deploy.sh
RUN chmod 700 /post-deploy.sh