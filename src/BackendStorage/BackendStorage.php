<?php

declare(strict_types=1);

namespace Keboola\BackupProject\BackendStorage;

use Keboola\BackupProject\Config\Config;

abstract class BackendStorage
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    abstract public function getCommandConfig(): array;
}
