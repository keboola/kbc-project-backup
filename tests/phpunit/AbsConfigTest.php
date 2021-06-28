<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Tests;

use Generator;
use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\ConfigDefinition;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class AbsConfigTest extends TestCase
{
    /**
     * @dataProvider validConfigDataProvider
     */
    public function testValidConfigTest(array $configArray, array $expectedConfig): void
    {
        $config = new AbsConfig($configArray, new ConfigDefinition());

        Assert::assertEquals($expectedConfig, $config->getParameters());
    }

    /**
     * @dataProvider invalidConfigDataProvider
     */
    public function testInvalidConfigTest(array $configArray, string $expectedMessage): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage($expectedMessage);

        new AbsConfig($configArray, new ConfigDefinition());
    }

    public function validConfigDataProvider(): Generator
    {
        yield 'full-config' => [
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'absRegion' => 'test-region',
                    'container' => 'test-container',
                    'accountName' => 'test-accountName',
                    '#accountKey' => 'test-accountKey',
                    'onlyStructure' => false,
                ],
            ],
            [
                'storageBackendType' => 'abs',
                'absRegion' => 'test-region',
                'container' => 'test-container',
                'accountName' => 'test-accountName',
                '#accountKey' => 'test-accountKey',
                'onlyStructure' => false,
            ],
        ];

        yield 'minimal-config' => [
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'absRegion' => 'test-region',
                    'container' => 'test-container',
                    'accountName' => 'test-accountName',
                    '#accountKey' => 'test-accountKey',
                ],
            ],
            [
                'storageBackendType' => 'abs',
                'absRegion' => 'test-region',
                'container' => 'test-container',
                'accountName' => 'test-accountName',
                '#accountKey' => 'test-accountKey',
                'onlyStructure' => false,
            ],
        ];
    }

    public function invalidConfigDataProvider(): Generator
    {
        yield 'missing-region' => [
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'container' => 'test-container',
                    'accountName' => 'test-accountName',
                    '#accountKey' => 'test-accountKey',
                ],
            ],
            'Missing required parameter "absRegion".',
        ];

        yield 'missing-container' => [
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'absRegion' => 'test-region',
                    'accountName' => 'test-accountName',
                    '#accountKey' => 'test-accountKey',
                ],
            ],
            'Missing required parameter "container".',
        ];

        yield 'missing-accountName' => [
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'absRegion' => 'test-region',
                    'container' => 'test-container',
                    '#accountKey' => 'test-accountKey',
                ],
            ],
            'Missing required parameter "accountName".',
        ];

        yield 'missing-accountKey' => [
            [
                'parameters' => [
                    'storageBackendType' => 'abs',
                    'absRegion' => 'test-region',
                    'container' => 'test-container',
                    'accountName' => 'test-accountName',
                ],
            ],
            'Missing required parameter "#accountKey".',
        ];
    }
}
