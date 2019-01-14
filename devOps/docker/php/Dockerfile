FROM panamedialottery/euromillions-base-php as base

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

COPY devOps/docker/php/cron/run-cron.sh /run-cron.sh
COPY devOps/docker/php/cron/euromillions.crons /etc/cron.d/euromillions.crons

RUN apt-get update && apt-get install -y cron \
    && chmod -v +x /run-cron.sh \
    && crontab /etc/cron.d/euromillions.crons \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*

CMD ["/run-cron.sh"]

################################################################################

FROM common-tasks-onbuild as prod

#TODO: move on build stage
RUN docker-php-ext-install opcache

# set php.ini
COPY devOps/docker/php/php-prod/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY devOps/docker/php/php-prod/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

# set opcache.ini
COPY devOps/docker/php/php-prod/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini

# set post-deploy script
COPY devOps/docker/php/php-prod/post-deploy.sh /post-deploy.sh
RUN chmod 700 /post-deploy.sh

################################################################################

FROM common-tasks-onbuild as staging

#TODO: move on build stage
RUN docker-php-ext-install opcache

# set php.ini
COPY devOps/docker/php/php-staging/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY devOps/docker/php/php-staging/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

# set opcache.ini
COPY devOps/docker/php/php-staging/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini

# set post-deploy script
COPY devOps/docker/php/php-staging/post-deploy.sh /post-deploy.sh
RUN chmod 700 /post-deploy.sh