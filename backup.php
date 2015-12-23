<?php

require "vendor/autoload.php";

require "config.php"

use OpenCloud\OpenStack;

$files = $argv[1];
$containerName = $argv[2];

// Initialization
$client = new OpenStack(
	$authUrl, array(
		"username"=> $username,
		"password"=> $password,
		"tenantName"  => $tenant
	)
);

$client->authenticate();
$service = $client->objectStoreService($serviceName, $region);
$container = $service->createContainer($containerName);

foreach (glob($files) as $filename) {
	echo "Sending $filename (" . number_format(filesize($filename) / 1024 / 1024, 2) . " MB)\n";
	$fileData = fopen($filename, "r+");
	$container->uploadObject(basename($filename), $fileData);
}


?>
