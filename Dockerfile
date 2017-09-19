FROM php:7.1
MAINTAINER Ondrej Hlavacek <ondrej.hlavacek@keboola.com>
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -q \
  && apt-get install unzip git libssl-dev -y --no-install-recommends \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /home

RUN curl --fail https://s3.amazonaws.com/keboola-storage-api-cli/builds/sapi-client.0.8.1.phar > /usr/local/bin/sapi-client \
  && chmod a+x /usr/local/bin/sapi-client
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

# Initialize
COPY . /home/

RUN composer install --no-interaction

CMD php /home/src/run.php --data=/data
