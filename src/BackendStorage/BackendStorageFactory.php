<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackendStorage;

use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\S3Config;

class BackendStorageFactory
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getBackendStorage(): BackendStorage
    {
        switch (get_class($this->config)) {
            case S3Config::class:
                return new S3BackendStorage($this->config);
            case AbsConfig::class:
                return new AbsBackendStorage($this->config);
            default:
                throw new \Exception('Unknown storage backend type.');
        }
    }
}
