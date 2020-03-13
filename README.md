# Image Scanner

## Setup
Run composer install to add dependencies



## Usage

From a command line/terminal run:
 
 ```php image-scanner.php {remoteUrl}```

Where remoteUrl is the FQDN that you want to scan

Default output will be a JSON encoded response

## Notes ##

This is ridiculously over-engineered! I've thrown additional components to play around with:

* [Composer](https://getcomposer.org/) for dependency management
* [PHP-DI](http://php-di.org/) as a simple Dependency Injection implementation
* [.env](https://github.com/vlucas/phpdotenv) Environment files implementation
* Interfaces
* Namespaces

## Licence

MIT

[Why did you choose this?](https://choosealicense.com/no-permission/)
