<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsConfig extends Config
{
    private string $region;

    private string $container;

    private string $accountName;

    private string $accountKey;

    private bool $onlyStructure;

    public function __construct(array $config, ?ConfigurationInterface $configDefinition = null)
    {
        parent::__construct($config, $configDefinition);

        $parameters = $this->validateConfig();

        $this->container = $parameters['container'];
        $this->accountName = $parameters['accountName'];
        $this->accountKey = $parameters['#accountKey'];
        $this->region = $parameters['absRegion'];
        $this->onlyStructure = $parameters['onlyStructure'];
    }

    private function validateConfig(): array
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setRequired('storageBackendType')
            ->setRequired('accountName')
            ->setRequired('#accountKey')
            ->setRequired('absRegion')
            ->setRequired('container')
            ->setRequired('onlyStructure')
            ->setDefined(['accountName', '#accountKey', 'onlyStructure']);

        return $resolver->resolve($this->config['parameters']);
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getContainer(): string
    {
        return $this->container;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getAccountKey(): string
    {
        return $this->accountKey;
    }

    public function getOnlyStructure(): bool
    {
        return $this->onlyStructure;
    }
}
