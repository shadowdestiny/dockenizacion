FROM nginx:stable as base

WORKDIR /var/www

################################################################################

FROM base as prod

COPY devOps/docker/web/prod/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devOps/docker/web/prod/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

################################################################################

FROM base as staging

COPY devOps/docker/web/staging/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devOps/docker/web/staging/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

################################################################################

FROM base as devel

COPY devOps/docker/web/devel/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devOps/docker/web/devel/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf




