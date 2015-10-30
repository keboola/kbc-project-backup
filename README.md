# Keboola Connection Project Backup

Keboola Connection project backup to AWS S3 implemented as docker bundle.


## Install & build

```
git clone https://github.com/keboola/kbc-project-backup.git
cd kbc-project-backup
curl https://s3.amazonaws.com/keboola-storage-api-cli/builds/sapi-client.0.2.10.phar > ./sapi-client.phar
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


- YAML configuration stored in `data/config.yml`
- KBC_TOKEN environment variable is required

### Sample configuration
Mapped to `/data/config.yml`

```
parameters:
  awsAccessKeyId: asdf
  "#awsSecretAccessKey": sadf
  s3bucket: test
  s3path: /

```
