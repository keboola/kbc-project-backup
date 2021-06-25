<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class S3Config extends Config
{
    private string $s3bucket;

    private string $s3path;

    private string $s3region;

    private bool $onlyStructure;

    private string $awsAccessKeyId;

    private string $awsSecretKey;

    public function __construct(array $config, ?ConfigurationInterface $configDefinition = null)
    {
        parent::__construct($config, $configDefinition);

        $parameters = $this->validateConfig();

        $this->awsAccessKeyId = $parameters['awsAccessKeyId'];
        $this->awsSecretKey = $parameters['#awsSecretAccessKey'] ?? $parameters['awsSecretAccessKey'];
        $this->s3bucket = $parameters['s3bucket'];
        $this->s3path = $parameters['s3path'] ?? '/';
        $this->s3region = $parameters['s3region'];
        $this->onlyStructure = $parameters['onlyStructure'];
    }

    private function validateConfig(): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            's3path' => '/',
        ]);
        $resolver
            ->setRequired('storageBackendType')
            ->setRequired('s3bucket')
            ->setRequired('awsAccessKeyId')
            ->setRequired('onlyStructure')
            ->setRequired('s3region')
            ->setDefined(['awsSecretAccessKey', '#awsSecretAccessKey', 'onlyStructure']);

        return $resolver->resolve($this->config['parameters']);
    }

    public function getS3bucket(): string
    {
        return $this->s3bucket;
    }

    public function getS3path(): string
    {
        return $this->s3path;
    }

    public function getS3region(): string
    {
        return $this->s3region;
    }

    public function getOnlyStructure(): bool
    {
        return $this->onlyStructure;
    }

    public function getAwsAccessKeyId(): string
    {
        return $this->awsAccessKeyId;
    }

    public function getAwsSecretKey(): string
    {
        return $this->awsSecretKey;
    }
}
