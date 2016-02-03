<?php

require "vendor/autoload.php";

use OpenCloud\OpenStack;

$longOptions = array(
	"help",
	"files:",
	"container:",
	"config:"
);
$parameters = getopt("h", $longOptions);

if (isset($parameters["help"]) || isset($parameters["h"])) {
	usage();
	exit(0);
}

if (count($parameters) !== 3) {
	echo "Required parameters are missing\n\n";
	usage();
	exit(1);
}

$files = $parameters["files"];
$containerName = $parameters["container"];
$configFile = $parameters["config"];

if (!file_exists($configFile)) {
	echo "The configuration file '$configFile' does not exist.\n\n";
	exit(2);
}

$ocParameters = json_decode(file_get_contents($configFile));

if (!$ocParameters) {
	echo "The configuration file '$configFile' content is not valid JSON.\n\n";
	exit(3);
}

// Initialization
$client = new OpenStack(
	$ocParameters->authUrl, array(
		"username"=> $ocParameters->username,
		"password"=> $ocParameters->password,
		"tenantName"  => $ocParameters->tenant
	)
);

$swiftUrl = $ocParameters->swiftUrl;
$serviceName = $ocParameters->serviceName;
$region = $ocParameters->region;

try {

	$client->authenticate();
	$service = $client->objectStoreService($serviceName, $region);
	$container = $service->createContainer($containerName);
	$container = $service->getContainer($containerName);

	foreach (glob($files) as $filename) {
		echo "Sending $filename (" . number_format(filesize($filename) / 1024 / 1024, 2) . " MB)\n";
		$fileData = fopen($filename, "r+");
		$container->uploadObject(basename($filename), $fileData);
	}

} catch (Exception $e) {
	echo "Problem while dealing with OpenCloud:\n";
	echo $e->getMessage() . "\n";
	exit(4);
}

function usage() {
	global $argv;
	echo basename($argv[0]) . "\n\n";
	echo "Backup files to an OpenCloud container\n\n";
	echo "Usage: " . basename($argv[0]) . " --files=*.jpeg --container=mycontainer --config=config.json\n\n";
	echo "Parameters:\n";
	echo " --help      : Displays this message.\n";
	echo " --files     : Files to backup, wildcards are allowed.\n";
	echo " --container : The container name, automatically created if it does not exist.\n";
	echo " --config    : Path to the configuration file.\n";
	echo "\n";
}

echo "Finished, bye!";

?>
