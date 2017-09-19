#!/bin/bash

docker pull quay.io/keboola/developer-portal-cli-v2:latest
docker run --rm -e KBC_DEVELOPERPORTAL_USERNAME=$KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD=$KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL=$KBC_DEVELOPERPORTAL_URL quay.io/keboola/developer-portal-cli-v2:latest ecr:get-login keboola kbc-project-backup
eval $(docker run --rm -e KBC_DEVELOPERPORTAL_USERNAME=$KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD=$KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL=$KBC_DEVELOPERPORTAL_URL quay.io/keboola/developer-portal-cli-v2:latest ecr:get-login keboola kbc-project-backup)
docker run --rm  -e KBC_DEVELOPERPORTAL_USERNAME=$KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD=$KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL=$KBC_DEVELOPERPORTAL_URL quay.io/keboola/developer-portal-cli-v2:latest ecr:get-repository keboola kbc-project-backup
export REPOSITORY=`docker run --rm  -e KBC_DEVELOPERPORTAL_USERNAME=$KBC_DEVELOPERPORTAL_USERNAME -e KBC_DEVELOPERPORTAL_PASSWORD=$KBC_DEVELOPERPORTAL_PASSWORD -e KBC_DEVELOPERPORTAL_URL=$KBC_DEVELOPERPORTAL_URL quay.io/keboola/developer-portal-cli-v2:latest ecr:get-repository keboola kbc-project-backup`
echo $REPOSITORY
docker tag keboola/kbc-project-backup:latest ${REPOSITORY}:${TRAVIS_TAG}
docker tag keboola/kbc-project-backup:latest ${REPOSITORY}:latest
docker push ${REPOSITORY}:${TRAVIS_TAG}
docker push ${REPOSITORY}:latest
