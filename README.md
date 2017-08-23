# Kai Richter \ Logger

This logger is based on the PSR-3 Logger Interface and enables simultaneous logging into multiple destinations, such as file, MongoDB, Slack, ...

## Wiki

You can find all information on using the Logger in the Wiki under: https://github.com/kairichter/logger/wiki

## Prerequisites

* [PHP](http://www.php.net) >= 7.1
* [PSR-Log](https://packagist.org/packages/psr/log) >= 1.0
* [Monolog](https://packagist.org/packages/monolog/monolog) >= 1.22
* [Symfony Var-Dumper](https://packagist.org/packages/symfony/var-dumper) >= 3.2
* [Symfony Console](https://packagist.org/packages/symfony/console) >= 3.2

## Other versions

You can find the Logger adapted for other PHP versions in the following branches:

* [PHP 7.0](https://github.com/kairichter/logger/tree/7.0)
* [PHP 5.6](https://github.com/kairichter/logger/tree/5.6)

## Installation

Through Composer, obviously:

```
composer require kairichter/logger
```

## Examples

In `vendor/kairichter/logger/examples/` you will find a detailed example of how to set up and use the Logger:

```
cd vendor/kairichter/logger/examples;
php quickstart.php
ls -lah
```

After the execution, a new file `example-YYYY-MM-DD.log` will be located in the new directory` logs`. This contains the log outputs of the file logger. 

