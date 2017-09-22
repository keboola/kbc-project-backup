#!/bin/sh
set -e

composer install --dev

php ./tests/createFixture.php
php ./tests/createConfig.php
php ./src/run.php --data=/home/tests/data
php ./tests/verify.php
