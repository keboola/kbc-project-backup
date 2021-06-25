<?php

declare(strict_types=1);

use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

require_once(__DIR__ . '/../vendor/autoload.php');

$config = [
    'parameters' => [
        'awsAccessKeyId' => getenv('AWS_ACCESS_KEY_ID'),
        '#awsSecretAccessKey' => getenv('AWS_SECRET_ACCESS_KEY'),
        's3bucket' => getenv('AWS_S3_BUCKET'),
        's3region' => getenv('AWS_REGION'),
        's3path' => getenv('AWS_S3_PATH'),
        'onlyStructure' => false,
    ],
];

if (!file_exists(__DIR__ . '/data/')) {
    mkdir(__DIR__ . '/data/', 0777, true);
}
$encode = new JsonEncode();
file_put_contents(__DIR__ . '/data/config.json', $encode->encode($config, JsonEncoder::FORMAT));
