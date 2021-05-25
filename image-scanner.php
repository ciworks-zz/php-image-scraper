<?php

require_once('./src/bootstrap.php');

$scriptName = $argv[0];
$remoteUrl = $argv[1];
$targetDir = $argv[2];

$logger->info(
    sprintf("Calling %s on remote URL %s", $scriptName, $remoteUrl)
);

if (empty($remoteUrl)) {
    echo "No url supplied!\n\n";
    exit();
}

try {
    $scanner->setFolderName($targetDir);
    $output = $scanner->fetch($remoteUrl);
    $scanner->scrape($output);

    echo "Script completed - " . count($output) . " images scraped\n";
    $logger->info(sprintf("Script completed - %d images scraped", count($output)));
} catch (Exception $exception) {
    echo "Script failed - " . $exception->getMessage();
    $logger->error("Script failed - " . $exception->getMessage());
}


