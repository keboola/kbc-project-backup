<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Tests;

use Keboola\BackupProject\BackupConfig\BackupConfigFactory;
use Keboola\BackupProject\Config\ConfigDefinition;
use Keboola\BackupProject\Config\S3Config;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class S3BackupConfigTest extends TestCase
{

    public function testGenerateConfig(): void
    {
        $config = new S3Config(
            [
                'parameters' => [
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                    'awsSecretAccessKey' => 'test-awsSecretAccessKey',
                    's3bucket' => 'test-s3bucket',
                    's3region' => 'test-s3region',
                    's3path' => 'test-s3path',
                ],
            ],
            new ConfigDefinition()
        );

        $backendFactory = new BackupConfigFactory($config);

        $backendStorage = $backendFactory->getBackupConfig();

        Assert::assertEquals(
            [
                'backupId' => '',
                'exportStructureOnly' => false,
                'storageBackendType' => 's3',
                'includeVersions' => true,
                'region' => 'test-s3region',
                'backupPath' => 'test-s3path/',
                'access_key_id' => 'test-awsAccessKeyId',
                '#secret_access_key' => 'test-awsSecretAccessKey',
                '#bucket' => 'test-s3bucket',
            ],
            $backendStorage->getConfig()
        );
    }
}
