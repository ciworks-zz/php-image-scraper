<?php

require "vendor/autoload.php";

$imageScannerClass = '\\CIWORKS\Classes\ImageScanner';
$funcName = 'fetch';

// position [0] is the script file name
$remoteUrl = $argv[1];

echo "\n";
echo "Calling '$argv[0] on remote URL $remoteUrl'...\n";
echo "*************************************************\n";

if (empty($remoteUrl)) {
    echo "Unable to process the scanner - supplied URL is not a string\n\n";
    exit();
}

$class = new $imageScannerClass;
$output = $class::fetch($remoteUrl);
$jsonOutput = json_encode($output);
echo $jsonOutput;
