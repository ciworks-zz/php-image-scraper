# Image Scraper:

## About ##

Originally intended to simply scrape a webpage for any embedded images, this tiny <acronymn title="Proof of Concept">POC</acronymn> that has morphed into an over-engineered solution! Primarily used to add and play with the technologies below:

* [Composer](https://getcomposer.org/) for dependency management
* [PHP-DI](http://php-di.org/) as a simple Dependency Injection implementation
* [.env](https://github.com/vlucas/phpdotenv) Environment files implementation
* [KLogger](https://github.com/katzgrau/KLogger) Simple PHP Logger
* Interfaces
* Namespaces

## Setup
Run ```composer install``` from the project root in a terminal to add dependencies

## Usage

From a command line/terminal run:
 
 ```php image-scanner.php {remoteUrl} {output_folder}```

Where ```remoteUrl``` is the <acronymn title="Fully Qualified Domain Name">FQDN</acronymn> that you want to scan

If no output folder is specified a default, `'output-' . (new \DateTime())->format('Ymd-His')` will be created and used.

## Environment variables

The `.env.example` allows you to set the underlying curl request output verbosity, output directory and logging directory

## Licence

MIT

[Why did you choose this?](https://choosealicense.com/no-permission/)
