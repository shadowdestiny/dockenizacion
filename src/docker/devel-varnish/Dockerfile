FROM ubuntu:18.04

# Let the container know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

# Update base image
# Add sources for latest nginx
# Install software requirements
RUN apt-get update && \
    apt-get install -y software-properties-common && \
    add-apt-repository ppa:nginx/development && \
    apt-get update && \
    apt-get upgrade -y && \
    apt-get -y install supervisor cron nginx varnish && \
    apt-get remove --purge -y software-properties-common && \
    apt-get autoremove -y && \
    apt-get clean && \
    apt-get autoclean && \
    echo -n > /var/lib/apt/extended_states && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /usr/share/man/?? && \
    rm -rf /usr/share/man/??_*

# Debug tools and other stuff (Remove for prod)
RUN apt-get update && \
    apt-get install -y net-tools vim curl

# nginx site conf
RUN rm -Rf /etc/nginx/conf.d/* && \
unlink /etc/nginx/sites-enabled/default && \
rm -Rf /etc/nginx/sites-available/default && \
mkdir -p /etc/nginx/ssl/
ADD ./nginx-site.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

# varnish conf
ADD ./default.vcl /etc/varnish/default.vcl

# Supervisor Config Files
ADD ./supervisord_nginx.conf /etc/supervisor/conf.d/supervisord_nginx.conf
ADD ./supervisord_varnish.conf /etc/supervisor/conf.d/supervisord_varnish.conf

# Start Supervisord
ADD ./start.sh /start.sh
RUN chmod 755 /start.sh

# Expose Ports
EXPOSE 80
EXPOSE 443

CMD ["/bin/bash", "/start.sh"]