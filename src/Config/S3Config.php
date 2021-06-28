<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Config;

use InvalidArgumentException;

class S3Config extends Config
{
    public function getS3bucket(): string
    {
        return $this->getValue(['parameters', 's3bucket']);
    }

    public function getS3path(): string
    {
        return rtrim($this->getValue(['parameters', 's3path'], '/'), '/') . '/';
    }

    public function getRegion(): string
    {
        return $this->getValue(['parameters', 's3region']);
    }

    public function getAwsAccessKeyId(): string
    {
        return $this->getValue(['parameters', 'awsAccessKeyId']);
    }

    public function getAwsSecretKey(): string
    {
        try {
            return $this->getValue(['parameters', '#awsSecretAccessKey']);
        } catch (InvalidArgumentException $e) {
            return $this->getValue(['parameters', 'awsSecretAccessKey']);
        }
    }
}
