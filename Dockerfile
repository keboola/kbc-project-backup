FROM quay.io/keboola/docker-base-php56:0.0.2
MAINTAINER Martin Halamicek <martin.halamicek@keboola.com>

WORKDIR /home

# Initialize
COPY . /home/
RUN curl --fail https://s3.amazonaws.com/keboola-storage-api-cli/builds/sapi-client.0.4.0.phar > ./sapi-client.phar
RUN composer install --no-interaction

ENTRYPOINT php /home/src/run.php --data=/data
