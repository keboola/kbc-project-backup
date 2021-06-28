<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackupConfig;

use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\S3Config;

class BackupConfigFactory
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getBackupConfig(): BackupConfig
    {
        switch (get_class($this->config)) {
            case S3Config::class:
                return new S3BackupConfig($this->config);
            case AbsConfig::class:
                return new AbsBackupConfig($this->config);
            default:
                throw new \Exception('Unknown storage backend type.');
        }
    }
}
