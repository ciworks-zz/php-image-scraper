<?php

use CiWorks\App\CurlRequest;
use CiWorks\App\ImageScanner;
use DI\Container;
use Katzgrau\KLogger\Logger;
use Psr\Log\LogLevel;

return [
    'app.base_output_folder' => $_ENV['OUTPUT_DIRECTORY'],
    'app.request.verbose' => $_ENV['REQUEST_VERBOSE'],
    'logging.dir' => $_ENV['LOGGING_DIRECTORY'],
    'Request' => function (Container $c) {
        $request = new CurlRequest();
        $request->setVerbosity($c->get('app.request.verbose'));
        return $request;
    },
    'Logger' => function (Container $c) {
        return new Logger($c->get('logging.dir'), LogLevel::INFO);
    },
    'ImageScraper' => function (Container $c) {
        return new ImageScanner(
            $c->get('Request'),
            $c->get('Logger'),
            $c->get('app.base_output_folder')
        );
    }
];
