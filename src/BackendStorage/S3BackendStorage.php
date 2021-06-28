<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackendStorage;

use Exception;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\S3Config;

class S3BackendStorage extends BackendStorage
{
    public function getCommandConfig(): array
    {
        if (!($this->config instanceof S3Config)) {
            throw new Exception();
        }

        return [
            'access_key_id' => $this->config->getAwsAccessKeyId(),
            '#secret_access_key' => $this->config->getAwsSecretKey(),
            'region' => $this->config->getRegion(),
            '#bucket' => $this->config->getS3bucket(),
            'backupPath' => $this->config->getS3path(),

            'backupId' => '',
            'exportStructureOnly' => $this->config->getOnlyStructure(),
            'storageBackendType' => Config::STORAGE_BACKEND_S3,
            'includeVersions' => true,
        ];
    }
}
