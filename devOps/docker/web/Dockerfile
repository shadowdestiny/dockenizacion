FROM nginx:stable as base

COPY shared.conf /etc/nginx/shared.conf

WORKDIR /var/www

################################################################################

FROM base as prod

COPY prod/nginx/nginx.conf /etc/nginx/nginx.conf
COPY prod/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

################################################################################

FROM base as staging

COPY staging/nginx/nginx.conf /etc/nginx/nginx.conf
COPY staging/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

################################################################################

FROM base as devel

COPY devel/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devel/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf