FROM panamedialottery/euromillions-base-php:prod_staging as base

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

# set editors for live coding on the containers
RUN apt-get update && apt-get install -y nano vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

################################################################################

FROM common-tasks-onbuild as cron

# set php.ini
COPY devOps/docker/php/cron/php.ini $PHP_INI_DIR/php.ini

COPY devOps/docker/php/cron/entrypoint.sh /entrypoint.sh
COPY devOps/docker/php/cron/euromillions.crons /etc/cron.d/euromillions.crons

RUN apt-get update && apt-get install -y cron supervisor mysql-client gzip \
    && chmod -v +x /entrypoint.sh \
    && crontab /etc/cron.d/euromillions.crons \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*

COPY devOps/docker/php/cron/supervisord.conf /etc/supervisor/supervisord.conf
COPY devOps/docker/php/cron/supervisor/services.conf /etc/supervisor/conf.d/services.conf

# tweak config file for get output on cron at production environment
RUN if [ -f /var/www/apps/shared/config/production_config.ini ]; then \
    sed -i s/error_reporting=0/error_reporting=E_ALL/g /var/www/apps/shared/config/production_config.ini \
    && sed -i s/display_errors=0/display_errors=1/g /var/www/apps/shared/config/production_config.ini; \
    fi

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

VOLUME /var/www

################################################################################

FROM common-tasks-onbuild as staging

ARG EM_ENVIRONMENT=staging

# set php.ini
COPY devOps/docker/php/php-staging/php.ini $PHP_INI_DIR/php.ini

# set fpm pool config
COPY devOps/docker/php/php-staging/php-fpm.d/www.conf $PHP_INI_DIR/../php-fpm.d/www.conf

# set post-deploy script
COPY devOps/docker/php/php-staging/post-deploy.sh /post-deploy.sh
RUN sed -i s/__EM_ENVIRONMENT__/$EM_ENVIRONMENT/g /post-deploy.sh \
    && chmod 700 /post-deploy.sh

VOLUME /var/www