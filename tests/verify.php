<?php
require_once(__DIR__ . "/../vendor/autoload.php");

// get transformation config id

$client = new \Keboola\StorageApi\Client([
    "token" => getenv("KBC_TOKEN"),
    "url" => getenv("KBC_URL")

]);

// delete configs
$components = new \Keboola\StorageApi\Components($client);
$componentsList = $components->listComponents(
    (new \Keboola\StorageApi\Options\Components\ListComponentsOptions())
    ->setInclude(["configuration"])
    ->setComponentType("transformation")
);

$configId = $componentsList[0]["configurations"][0]["id"];

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
$keys = array_map(function ($key) {
    return $key["Key"];
}, $objects->toArray()['Contents']);

$expected = [
    "backup/buckets.json",
    "backup/tables.json",
    "backup/configurations.json",
    "backup/configurations/transformation/" . $configId . ".json",
    "backup/in/c-main/sample.part_0.csv.gz"
];
if (count(array_diff($expected, $keys)) > 0 or count(array_diff($keys, $expected))) {
    var_export($expected);
    print "does not match S3 keys\n";
    var_export($keys);
    exit(1);
}
