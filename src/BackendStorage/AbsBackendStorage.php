<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackendStorage;

use Exception;
use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\S3Config;

class AbsBackendStorage extends BackendStorage
{
    protected function buildConfig(array $config): Config
    {
        return new AbsConfig($config);
    }

    public function getCommandConfig(): array
    {
        if (!($this->config instanceof AbsConfig)) {
            throw new Exception();
        }

        return [
            'accountName' => $this->config->getAccountName(),
            '#accountKey' => $this->config->getAccountKey(),
            'region' => $this->config->getRegion(),
            'backupPath' => $this->config->getContainer(),

            'backupId' => '',
            'exportStructureOnly' => $this->config->getOnlyStructure(),
            'storageBackendType' => Config::STORAGE_BACKEND_ABS,
            'includeVersions' => true,
        ];
    }
}
