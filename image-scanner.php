<?php

header("Content-Type: application/json");

// Autoloading and DI prerequisites
require_once('./src/bootstrap.php');

$scriptName = $argv[0];
$remoteUrl = $argv[1];

printf("\nCalling %s on remote URL %s\n", $scriptName, $remoteUrl);

// banal formatting
$asx = str_repeat('*', strlen($remoteUrl));

echo "****************************************$asx\n";

if (empty($remoteUrl)) {
    echo "Unable to process the scanner - supplied URL is not a string\n\n";
    exit();
}

$output = $imageScanner->fetch($remoteUrl);

echo json_encode($output);

echo "\n****************************************$asx\n";
echo "Script completed - " . count($output) . " links returned\n";
