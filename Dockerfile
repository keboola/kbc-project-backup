FROM php:7.1-alpine
MAINTAINER Ondrej Hlavacek <ondrej.hlavacek@keboola.com>
ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_ALLOW_SUPERUSER 1

# Deps
RUN apk add --no-cache wget git unzip gzip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

WORKDIR /home/storage-api-cli

RUN git clone -b 1.0.0 https://github.com/keboola/storage-api-cli /home/storage-api-cli \
    && composer install --no-interaction

WORKDIR /home/kbc-project-backup

# Initialize
COPY . /home/kbc-project-backup

RUN composer install --no-interaction

CMD php /home/kbc-project-backup/src/run.php --data=/data
