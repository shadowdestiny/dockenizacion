FROM nginx:stable as base

COPY shared.conf /etc/nginx/shared.conf

WORKDIR /var/www

################################################################################

FROM base as prod

COPY prod/nginx/nginx.conf /etc/nginx/nginx.conf
COPY prod/nginx/conf.d /etc/nginx/conf.d
RUN ln -sf /dev/stdout /var/log/nginx/cache.log

################################################################################

FROM base as staging

ARG EM_ENVIRONMENT=staging
ARG EM_DOMAIN=beta.euromillions.com

COPY staging/nginx/nginx.conf /etc/nginx/nginx.conf
COPY staging/nginx/conf.d /etc/nginx/conf.d
RUN ln -sf /dev/stdout /var/log/nginx/cache.log \
    && sed -i s/__EM_ENVIRONMENT__/$EM_ENVIRONMENT/g /etc/nginx/conf.d/default.conf \
    && sed -i s/__EM_DOMAIN__/$EM_DOMAIN/g /etc/nginx/conf.d/default.conf

################################################################################

FROM base as devel

COPY devel/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devel/nginx/conf.d /etc/nginx/conf.d
RUN ln -sf /dev/stdout /var/log/nginx/cache.log