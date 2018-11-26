FROM nginx:stable as base

# DEBUG
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y vim \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*
############

WORKDIR /var/www

################################################################################

FROM base as prod

COPY devOps/docker/web/prod/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devOps/docker/web/prod/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

################################################################################

FROM base as staging

COPY devOps/docker/web/staging/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devOps/docker/web/staging/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

# ssl
COPY devOps/docker/web/staging/nginx/ssl/nginx.crt /etc/nginx/ssl/nginx.crt
COPY devOps/docker/web/staging/nginx/ssl/nginx.key /etc/nginx/ssl/nginx.key

################################################################################

FROM base as devel

COPY devOps/docker/web/devel/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devOps/docker/web/devel/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf




