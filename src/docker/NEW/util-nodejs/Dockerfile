FROM node:8-jessie

COPY ./provision-react.sh /provision-react.sh
COPY ./provision-grunt.sh /provision-grunt.sh

RUN chmod 755 /provision-react.sh \
    && chown node:node /provision-react.sh \
    && chmod 755 /provision-grunt.sh \
    && chown node:node /provision-grunt.sh

USER node