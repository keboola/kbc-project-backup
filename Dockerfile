FROM php:7.1-alpine
MAINTAINER Ondrej Hlavacek <ondrej.hlavacek@keboola.com>
ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_ALLOW_SUPERUSER 1

# Deps
RUN apk add --no-cache wget git unzip gzip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

WORKDIR /home

# Initialize
COPY . /home

RUN composer install --no-interaction

CMD php /home/src/run.php --data=/data
