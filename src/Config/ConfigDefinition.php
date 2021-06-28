<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Config;

use Keboola\Component\Config\BaseConfigDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigDefinition extends BaseConfigDefinition
{
    protected function getParametersDefinition(): ArrayNodeDefinition
    {
        $parametersNode = parent::getParametersDefinition();
        // @formatter:off
        /** @noinspection NullPointerExceptionInspection */
        $parametersNode
            ->validate()->always(function ($v) {
                switch ($v['storageBackendType']) {
                    case Config::STORAGE_BACKEND_S3:
                        $requiredItems = [
                            's3bucket',
                            's3path',
                            's3region',
                            'awsAccessKeyId',
                        ];
                        foreach ($requiredItems as $item) {
                            if (empty($v[$item])) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Missing required parameter "%s".',
                                    $item
                                ));
                            }
                        }

                        //BC break
                        if (empty($v['#awsSecretAccessKey']) && empty($v['awsSecretAccessKey'])) {
                            throw new InvalidConfigurationException(
                                'Missing required parameter "#awsSecretAccessKey".'
                            );
                        }

                        break;
                    case Config::STORAGE_BACKEND_ABS:
                        foreach (['absRegion', 'accountName', '#accountKey', 'container'] as $item) {
                            if (empty($v[$item])) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Missing required parameter "%s".',
                                    $item
                                ));
                            }
                        }
                        break;
                    default:
                        throw new InvalidConfigurationException('Unknown storage backend type.');
                }
                return $v;
            })->end()
            ->children()
                ->scalarNode('storageBackendType')
                    ->defaultValue(Config::STORAGE_BACKEND_S3)
                ->end()
                ->booleanNode('onlyStructure')->defaultFalse()->end()
                ->scalarNode('s3bucket')->end()
                ->scalarNode('s3path')->end()
                ->scalarNode('s3region')->end()
                ->scalarNode('awsAccessKeyId')->end()
                ->scalarNode('#awsSecretAccessKey')->end()
                ->scalarNode('awsSecretAccessKey')->end()
                ->scalarNode('absRegion')->end()
                ->scalarNode('container')->end()
                ->scalarNode('accountName')->end()
                ->scalarNode('#accountKey')->end()
            ->end()
        ;
        // @formatter:on
        return $parametersNode;
    }
}
