# Keboola Connection Project Backup

[![Build Status](https://travis-ci.org/keboola/kbc-project-backup.svg?branch=master)](https://travis-ci.org/keboola/kbc-project-backup)

Keboola Connection project backup to AWS S3 implemented as a Docker container.

## Install & build

```
git clone https://github.com/keboola/kbc-project-backup.git
cd kbc-project-backup
docker-compose build
```


## Run
Create `.env` file from this template

```
KBC_TOKEN=
KBC_URL=
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_S3_BUCKET=
AWS_REGION=
AWS_S3_PATH=
```

- `KBC_*` variables are from the project you want to backup
- `AWS_*` variables are from the S3 bucket the backup will be stored to; use [aws-cf-template.json](./aws-cf-template.json) CloudFormation stack template to create all required AWS resources

Then run this command to run the backup 

```
docker-compose run app
```

## Development

```
docker-compose run tests

```
