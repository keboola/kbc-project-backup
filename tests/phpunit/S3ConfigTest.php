<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Tests;

use Generator;
use Keboola\BackupProject\Config\ConfigDefinition;
use Keboola\BackupProject\Config\S3Config;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class S3ConfigTest extends TestCase
{
    /**
     * @dataProvider validConfigDataProvider
     */
    public function testValidConfigTest(array $configArray, array $expectedConfig): void
    {
        $config = new S3Config($configArray, new ConfigDefinition());

        Assert::assertEquals($expectedConfig, $config->getParameters());
    }

    /**
     * @dataProvider invalidConfigDataProvider
     */
    public function testInvalidConfigTest(array $configArray, string $expectedMessage): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage($expectedMessage);

        new S3Config($configArray, new ConfigDefinition());
    }

    public function validConfigDataProvider(): Generator
    {
        yield 'full-config' => [
            [
                'parameters' => [
                    'storageBackendType' => 's3',
                    's3bucket' => 'test-s3bucket',
                    's3path' => 'test-s3path',
                    's3region' => 'test-s3region',
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                    '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                    'onlyStructure' => false,
                ],
            ],
            [
                'storageBackendType' => 's3',
                's3bucket' => 'test-s3bucket',
                's3path' => 'test-s3path',
                's3region' => 'test-s3region',
                'awsAccessKeyId' => 'test-awsAccessKeyId',
                '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                'onlyStructure' => false,
            ]
        ];

        yield 'minimal-config' => [
            [
                'parameters' => [
                    's3bucket' => 'test-s3bucket',
                    's3path' => 'test-s3path',
                    's3region' => 'test-s3region',
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                    '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                ],
            ],
            [
                'storageBackendType' => 's3',
                's3bucket' => 'test-s3bucket',
                's3path' => 'test-s3path',
                's3region' => 'test-s3region',
                'awsAccessKeyId' => 'test-awsAccessKeyId',
                '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                'onlyStructure' => false,
            ]
        ];
    }

    public function invalidConfigDataProvider(): Generator
    {
        yield 'missing-s3bucket' => [
            [
                'parameters' => [
                    's3path' => 'test-s3path',
                    's3region' => 'test-s3region',
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                    '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                ],
            ],
            'Missing required parameter "s3bucket".'
        ];

        yield 'missing-s3path' => [
            [
                'parameters' => [
                    's3bucket' => 'test-s3bucket',
                    's3region' => 'test-s3region',
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                    '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                ],
            ],
            'Missing required parameter "s3path".'
        ];

        yield 'missing-s3region' => [
            [
                'parameters' => [
                    's3bucket' => 'test-s3bucket',
                    's3path' => 'test-s3path',
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                    '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                ],
            ],
            'Missing required parameter "s3region".'
        ];

        yield 'missing-awsAccessKeyId' => [
            [
                'parameters' => [
                    's3bucket' => 'test-s3bucket',
                    's3path' => 'test-s3path',
                    's3region' => 'test-s3region',
                    '#awsSecretAccessKey' => 'test-awsSecretAccessKey',
                ],
            ],
            'Missing required parameter "awsAccessKeyId".'
        ];

        yield 'missing-awsSecretAccessKey' => [
            [
                'parameters' => [
                    's3bucket' => 'test-s3bucket',
                    's3path' => 'test-s3path',
                    's3region' => 'test-s3region',
                    'awsAccessKeyId' => 'test-awsAccessKeyId',
                ],
            ],
            'Missing required parameter "#awsSecretAccessKey".'
        ];
    }
}
