<?php

declare(strict_types=1);

namespace Keboola\BackupProject\Config;

use Exception;
use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    public const STORAGE_BACKEND_S3 = 's3';

    public const STORAGE_BACKEND_ABS = 'abs';

    public function getKbcUrl(): string
    {
        $url = getenv('KBC_URL');
        if (!$url) {
            throw new Exception('KBC_URL must be set');
        }
        return $url;
    }

    public function getKbcToken(): string
    {
        $token = getenv('KBC_TOKEN');
        if (!$token) {
            throw new Exception('KBC_TOKEN must be set');
        }
        return $token;
    }
}
