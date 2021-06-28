<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackupConfig;

use Exception;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\S3Config;

class S3BackupConfig extends BackupConfig
{
    public function getConfig(): array
    {
        if (!($this->config instanceof S3Config)) {
            throw new Exception();
        }

        $this->storageBackend = Config::STORAGE_BACKEND_S3;

        return array_merge(
            parent::getConfig(),
            [
                'access_key_id' => $this->config->getAwsAccessKeyId(),
                '#secret_access_key' => $this->config->getAwsSecretKey(),
                'region' => $this->config->getRegion(),
                '#bucket' => $this->config->getS3bucket(),
                'backupPath' => $this->config->getS3path(),
            ]
        );
    }
}
