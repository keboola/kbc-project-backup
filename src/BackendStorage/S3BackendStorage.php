<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackendStorage;

use Exception;
use Keboola\BackupProject\Config\S3Config;

class S3BackendStorage extends BackendStorage
{
    private const STORAGE_BACKEND = 's3';

    public function getCommandConfig(): array
    {
        if (!($this->config instanceof S3Config)) {
            throw new Exception();
        }

        return [
            'backupId' => '',
            'access_key_id' => $this->config->getAwsAccessKeyId(),
            '#secret_access_key' => $this->config->getAwsSecretKey(),
            'region' => $this->config->getS3region(),
            '#bucket' => $this->config->getS3bucket(),
            'backupPath' => $this->config->getS3path(),
            'exportStructureOnly' => $this->config->getOnlyStructure(),
            'storageBackendType' => self::STORAGE_BACKEND,
            'includeVersions' => true,
        ];
    }
}
