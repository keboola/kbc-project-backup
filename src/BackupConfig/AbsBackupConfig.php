<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackupConfig;

use Exception;
use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\Config;

class AbsBackupConfig extends BackupConfig
{
    public function getConfig(): array
    {
        if (!($this->config instanceof AbsConfig)) {
            throw new Exception();
        }

        $this->storageBackend = Config::STORAGE_BACKEND_ABS;

        return array_merge(
            parent::getConfig(),
            [
                'accountName' => $this->config->getAccountName(),
                '#accountKey' => $this->config->getAccountKey(),
                'region' => $this->config->getRegion(),
                'backupPath' => $this->config->getContainer(),
            ]
        );
    }
}
