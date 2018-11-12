FROM nginx:stable as devel

COPY devel/nginx/nginx.conf /etc/nginx/nginx.conf
COPY devel/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

FROM nginx:stable as staging

COPY staging/nginx/nginx.conf /etc/nginx/nginx.conf
COPY staging/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

#FROM nginx:stable as prod
#
#COPY nginx/prod/nginx.conf /etc/nginx/nginx.conf
#COPY nginx/prod/conf.d/default.conf /etc/nginx/conf.d/default.conf