<?php
require_once(__DIR__ . "/../vendor/autoload.php");

print "Setting up fixtures\n";

$client = new \Keboola\StorageApi\Client([
    "token" => getenv("KBC_TOKEN"),
    "url" => getenv("KBC_URL")
]);

// delete buckets
$buckets = $client->listBuckets();
foreach ($buckets as $bucket) {
    $client->dropBucket($bucket["id"], ["force" => true]);
}

// delete configs
$components = new \Keboola\StorageApi\Components($client);
$componentsList = $components->listComponents(
    (new \Keboola\StorageApi\Options\Components\ListComponentsOptions())
    ->setInclude(["configuration"])
);
foreach ($componentsList as $component) {
    foreach ($component["configurations"] as $configuration) {
        $components->deleteConfiguration($component["id"], $configuration["id"]);
    }
}

print "Storage purged\n";

// purge S3
$s3Client = new \Aws\S3\S3Client([
    "version" => "2006-03-01",
    "region" => getenv("AWS_REGION"),
    "credentials" => [
        "key" => getenv("AWS_ACCESS_KEY_ID"),
        "secret" => getenv("AWS_SECRET_ACCESS_KEY")
    ]
]);
$objects = $s3Client->listObjects([
    "Bucket" => getenv("AWS_S3_BUCKET"),
    "Prefix" => getenv("AWS_S3_PATH")
]);
$keys = $objects->toArray()['Contents'];

if (count($keys) > 0) {
    $s3Client->deleteObjects([
        'Bucket' => getenv("AWS_S3_BUCKET"),
        'Delete' => ['Objects' => $keys]
    ]);
}

print "AWS S3 purged\n";

// create bucket and table
$client->createBucket("main", \Keboola\StorageApi\Client::STAGE_IN);
$client->createTableAsync("in.c-main", "sample", new \Keboola\Csv\CsvFile(__DIR__ . "/sample.csv"));

print "Created table and bucket\n";

// create config
$components->addConfiguration(
    (new \Keboola\StorageApi\Options\Components\Configuration())
    ->setComponentId("transformation")
    ->setConfiguration(["name" => "test"])
    ->setName("test")
);

print "Created config\n";
