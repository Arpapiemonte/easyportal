ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-apache-bullseye as base

WORKDIR /var/www/html
COPY ./docker/apache-site.conf /etc/apache2/sites-available/000-default.conf
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=IT/ST=Italy/L=Turin/O=Security/OU=Development/CN=example.com"
RUN a2enmod rewrite && a2ensite default-ssl && a2enmod ssl
RUN apt-get update && apt-get install --no-install-recommends -y \
    libldap2-dev unzip libpq-dev libaio1\
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install ldap

COPY ./docker/entrypoint.sh /usr/local/bin
EXPOSE 80
EXPOSE 443
