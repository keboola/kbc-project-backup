<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Config;

class AbsConfig extends Config
{
    public function getRegion(): string
    {
        return $this->getValue(['parameters', 'absRegion']);
    }

    public function getContainer(): string
    {
        return $this->getValue(['parameters', 'container']);
    }

    public function getAccountName(): string
    {
        return $this->getValue(['parameters', 'accountName']);
    }

    public function getAccountKey(): string
    {
        return $this->getValue(['parameters', '#accountKey']);
    }

    public function getOnlyStructure(): bool
    {
        return $this->getValue(['parameters', 'onlyStructure']);
    }
}
