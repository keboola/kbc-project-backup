<?php
use Symfony\Component\OptionsResolver\OptionsResolver;
use Keboola\StorageApi\Cli\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

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

$app = new Application();

$newOptions = [];
if (!in_array('--no-ansi', $_SERVER['argv'])) {
    $newOptions[] = '--ansi';
}
$newOptions[] = '--token=' . $token;
$newOptions[] = '--url=' . $url;
$newOptions[] = 'backup-project';
$newOptions[] = $parameters['s3bucket'];
$newOptions[] = $parameters['s3path'] == '' ? '/' : $parameters['s3path'];
$newOptions[] = $parameters['s3region'];
$newOptions[] = '--include-versions';
if ($parameters['onlyStructure']) {
    $newOptions[] = '--structure-only';
}

$input = new ArgvInput($newOptions);
$app->run($input);
