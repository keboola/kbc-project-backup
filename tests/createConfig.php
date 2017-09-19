<?php
require_once(__DIR__ . "/../vendor/autoload.php");

$config = [
  "parameters" => [
    "awsAccessKeyId" => getenv("AWS_ACCESS_KEY_ID"),
    "#awsSecretAccessKey" => getenv("AWS_SECRET_ACCESS_KEY"),
    "s3bucket" => getenv("AWS_S3_BUCKET"),
    "s3region" => getenv("AWS_REGION"),
    "s3path" => getenv("AWS_S3_PATH"),
    "onlyStructure" => false
  ]
];

$encode = new \Symfony\Component\Serializer\Encoder\JsonEncode();
file_put_contents(__DIR__ . "/data/config.json", $encode->encode($config, \Symfony\Component\Serializer\Encoder\JsonEncoder::FORMAT));
