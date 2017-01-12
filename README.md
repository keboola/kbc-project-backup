# Keboola Connection Project Backup

Keboola Connection project backup to AWS S3 implemented as docker bundle.

## Install & build

```
git clone https://github.com/keboola/kbc-project-backup.git
cd kbc-project-backup
curl https://s3.amazonaws.com/keboola-storage-api-cli/builds/sapi-client.0.5.0.phar > ./sapi-client.phar
composer install
```

## Run
```
php ./src/run.php --data=./tests/data
```

Where `/data` goes to your data folder.


## Data directory

Data directory must follow conventions defined in [Keboola Docker Bundle repository](https://github.com/keboola/docker-bundle).

## Configuration

AWS credentials with permission to write S3 bucket are required. You can create it using Cloudformation template [s3.template.json](https://github.com/keboola/kbc-project-backup/blob/master/s3.template.json).

YAML configuration stored in `data/config.yml`

```
parameters:
  awsAccessKeyId: asdf
  "#awsSecretAccessKey": sadf
  s3bucket: test
  s3path: /
```

### Environment variables
- `KBC_URL` - URL of keboola connection stack eq. `https://connection.keboola.com`
- `KBC_TOKEN` - Project Storage API token

### Sample configuration
Mapped to `/data/config.yml`

```
parameters:
  awsAccessKeyId: asdf
  "#awsSecretAccessKey": sadf
  s3bucket: test
  s3path: /
```
