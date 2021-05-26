<?php

use DI\ContainerBuilder;

require_once("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$builder = new ContainerBuilder();
$builder->enableCompilation(__DIR__ . '/tmp');
$builder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
$builder->addDefinitions('config.php');
$container = $builder->build();

$scanner = $container->get('ImageScraper');
$logger = $container->get('Logger');
