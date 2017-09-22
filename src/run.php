<?php

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

$url = getenv('KBC_URL');
if (!$url) {
    print "KBC_URL must be set";
    exit(1);
}

$resolver = new OptionsResolver();
$resolver->setDefaults([
	's3path' => '/'
]);
$resolver
	->setRequired('s3bucket')
	->setRequired('awsAccessKeyId')
	->setRequired('onlyStructure')
	->setRequired('s3region')
	->setDefined(['awsSecretAccessKey', '#awsSecretAccessKey', 'onlyStructure']);

$jsonDecode = new \Symfony\Component\Serializer\Encoder\JsonDecode(true);
$config = $jsonDecode->decode(
    file_get_contents($arguments['data'] . '/config.json'),
    \Symfony\Component\Serializer\Encoder\JsonEncoder::FORMAT
);

try {
	$parameters = $resolver->resolve($config['parameters']);
	$awsSecretKey = $parameters['#awsSecretAccessKey'] ? $parameters['#awsSecretAccessKey'] : $parameters['awsSecretAccessKey'];
} catch (\Exception $e) {
	print $e->getMessage();
	exit(1);
}

putenv("AWS_ACCESS_KEY_ID={$parameters['awsAccessKeyId']}");
putenv("AWS_SECRET_ACCESS_KEY={$awsSecretKey}");

$return = null;
$cmd = '/home/vendor/keboola/storage-api-cli/bin/cli --no-ansi --token=' .
	escapeshellarg($token) .
    ' --url=' .
    escapeshellarg($url) .
	' backup-project ' .
	escapeshellarg($parameters['s3bucket']) .
	' ' .
	escapeshellarg($parameters['s3path'] == '' ? '/' : $parameters['s3path']) .
	' ' .
	escapeshellarg($parameters['s3region']) . 
	($parameters['onlyStructure'] ? '  --structure-only' : '') .
	' --include-versions '
;

passthru($cmd, $return);

exit($return);
