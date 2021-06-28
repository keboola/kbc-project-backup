<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Tests;

use Keboola\BackupProject\BackupConfig\BackupConfigFactory;
use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\ConfigDefinition;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AbsBackupConfigTest extends TestCase
{

    public function testGenerateConfig(): void
    {
        $config = new AbsConfig(
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'absRegion' => 'test-region',
                    'container' => 'test-container',
                    'accountName' => 'test-accountName',
                    '#accountKey' => 'test-accountKey',
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
                'storageBackendType' => 'abs',
                'includeVersions' => true,
                'accountName' => 'test-accountName',
                '#accountKey' => 'test-accountKey',
                'region' => 'test-region',
                'backupPath' => 'test-container',
            ],
            $backendStorage->getConfig()
        );
    }
}
