<?php

require_once("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new DI\Container();
$imageScanner = $container->get('CiWorks\App\ImageScanner');
