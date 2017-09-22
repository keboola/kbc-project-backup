#!/bin/sh
set -e

docker pull quay.io/keboola/developer-portal-cli-v2:latest
export REPOSITORY=`docker run --rm  \
    -e KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL \
    quay.io/keboola/developer-portal-cli-v2:latest ecr:get-repository keboola kbc-project-takeout`
docker tag keboola/kbc-project-backup:latest ${REPOSITORY}:${TRAVIS_TAG}
docker tag keboola/kbc-project-backup:latest ${REPOSITORY}:latest
eval $(docker run --rm \
    -e KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL \
    quay.io/keboola/developer-portal-cli-v2:latest ecr:get-login keboola kbc-project-takeout)
docker push ${REPOSITORY}:${TRAVIS_TAG}
docker push ${REPOSITORY}:latest

docker run --rm -e KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL \
    quay.io/keboola/developer-portal-cli-v2:latest update-app-repository keboola kbc-project-takeout ${TRAVIS_TAG}

