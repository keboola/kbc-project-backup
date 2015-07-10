<?php

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\OptionsResolver\OptionsResolver;

require_once(dirname(__FILE__) . "/../vendor/autoload.php");

$arguments = getopt("d::", ["data::"]);
if (!isset($arguments["data"])) {
	print "Data folder not set.";
	exit(1);
}

$token = getenv('KBC_TOKEN');
if (!$token) {
	print "KBC_TOKEN must be set";
	exit(1);
}

$resolver = new OptionsResolver();
$resolver->setDefaults([
	's3path' => '/'
]);
$resolver
	->setRequired('s3bucket')
	->setRequired('awsAccessKeyId')
	->setRequired('awsSecretAccessKey');


$config = Yaml::parse(file_get_contents($arguments["data"] . "/config.yml"));

try {
	$parameters = $resolver->resolve($config['parameters']);
} catch (\Exception $e) {
	print $e->getMessage();
	exit(1);
}

putenv("AWS_ACCESS_KEY_ID={$parameters['awsAccessKeyId']}");
putenv("AWS_SECRET_ACCESS_KEY={$parameters['awsSecretAccessKey']}");

$return = null;
passthru('php ' . __DIR__ . '/../sapi-client.phar --no-ansi --token=' .
	escapeshellarg($token) .
	' backup-project ' .
	escapeshellarg($parameters['s3bucket']) .
	' ' .
	escapeshellarg($parameters['s3path'])
, $return);

exit($return);