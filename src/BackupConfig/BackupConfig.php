<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackupConfig;

use Keboola\BackupProject\Config\Config;

abstract class BackupConfig
{
    protected Config $config;

    protected string $storageBackend;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return [
            'backupId' => '',
            'exportStructureOnly' => $this->config->getOnlyStructure(),
            'storageBackendType' => $this->storageBackend,
            'includeVersions' => true,
        ];
    }
}
