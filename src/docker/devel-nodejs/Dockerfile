FROM node:8-jessie

COPY ./provision.sh /provision.sh

RUN chmod 755 /provision.sh \
    && chown node:node /provision.sh

USER node