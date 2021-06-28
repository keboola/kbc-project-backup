<?php

declare(strict_types=1);

namespace Keboola\BackupProject;

use Exception;
use Keboola\BackupProject\BackupConfig\BackupConfigFactory;
use Keboola\BackupProject\Config\AbsConfig;
use Keboola\BackupProject\Config\Config;
use Keboola\BackupProject\Config\ConfigDefinition;
use Keboola\BackupProject\Config\S3Config;
use Keboola\Component\BaseComponent;
use Keboola\Component\UserException;
use Keboola\StorageApi\Client;
use Keboola\StorageApi\Client as StorageClient;
use Keboola\Syrup\Client as SyrupClient;

class Component extends BaseComponent
{
    private const SYRUP_SERVICE_ID = 'syrup';

    protected function run(): void
    {
        $sapiClient = new StorageClient([
            'url' => $this->getConfig()->getKbcUrl(),
            'token' => $this->getConfig()->getKbcToken(),
        ]);

        $syrupClient = $this->createSyrupClientFromStorageClient($sapiClient);

        $backendFactory = new BackupConfigFactory($this->getConfig());
        $backendStorage = $backendFactory->getBackupConfig();

        $jobConfig = $backendStorage->getConfig();

        $this->runJob($syrupClient, $jobConfig);
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

    public function createSyrupClientFromStorageClient(Client $sapiClient): SyrupClient
    {
        $services = $sapiClient->indexAction()['services'];
        $baseUrl = self::getKeboolaServiceUrl(
            $services,
            self::SYRUP_SERVICE_ID
        );

        return new SyrupClient([
            'url' => $baseUrl,
            'token' => $sapiClient->getTokenString(),
            'super' => 'docker',
            'runId' => $sapiClient->getRunId(),
        ]);
    }

    private function getKeboolaServiceUrl(array $services, string $serviceId): string
    {
        $foundServices = array_values(array_filter($services, function ($service) use ($serviceId) {
            return $service['id'] === $serviceId;
        }));
        if (empty($foundServices)) {
            throw new Exception(sprintf('%s service not found', $serviceId));
        }
        return $foundServices[0]['url'];
    }

    private function runJob(SyrupClient $syrupClient, array $jobConfig): void
    {
        $this->getLogger()->info('Creating source project snapshot');
        $job = $syrupClient->runJob(
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
}
