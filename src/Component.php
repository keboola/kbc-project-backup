<?php

declare(strict_types=1);

namespace Keboola\BackupProject;

use Exception;
use Keboola\BackupProject\BackendStorage\BackendStorageFactory;
use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\ConfigDefinition;
use Keboola\BackupProject\Config\S3Config;
use Keboola\Component\BaseComponent;
use Keboola\Component\UserException;
use Keboola\StorageApi\Client as StorageClient;

class Component extends BaseComponent
{
    protected function run(): void
    {
        $backendFactory = new BackendStorageFactory($this->getConfig());
        $backendStorage = $backendFactory->getBackendStorage();

        $jobConfig = $backendStorage->getCommandConfig();

        $sapiClient = new StorageClient([
            'url' => $this->getConfig()->getKbcUrl(),
            'token' => $this->getConfig()->getKbcToken(),
        ]);

        $dockerRunner = Utils::createDockerRunnerClientFromStorageClient($sapiClient);

        $this->getLogger()->info('Creating source project snapshot');
        $job = $dockerRunner->runJob(
            'keboola.project-backup',
            [
                'configData' => [
                    'parameters' => $jobConfig,
                ],
                'tag' => 'TEST-COM-882-3',
            ]
        );
        if ($job['status'] !== 'success') {
            throw new Exception('Project snapshot create error: ' . $job['result']['message']);
        }
        $this->getLogger()->info('Source project snapshot created');
    }

    public function getConfig(): Config
    {
        /** @var Config $config */
        $config = parent::getConfig();
        return $config;
    }

    protected function getConfigClass(): string
    {
        $storageBackendType = $this->getRawConfig()['parameters']['storageBackendType'];
        switch ($storageBackendType) {
            case Config::STORAGE_BACKEND_S3:
                return S3Config::class;
            case Config::STORAGE_BACKEND_ABS:
                return AbsConfig::class;
            default:
                throw new UserException(sprintf(
                    'Unknown storage backend type "%s".',
                    $storageBackendType
                ));
        }
    }

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }
}
