# Kai Richter \ Logger

This logger is based on the PSR-3 Logger Interface and enables simultaneous logging into multiple destinations, such as file, MongoDB, Slack, ...

> **This is the PHP 7.0 version!**

## Documentation

You can find all information on using the Logger in the Wiki under: https://github.com/kairichter/logger/wiki

## Prerequisites

* [PHP](http://www.php.net) >= 7.0
* [PSR-Log](https://packagist.org/packages/psr/log) >= 1.0
* [Monolog](https://packagist.org/packages/monolog/monolog) >= 1.22
* [Symfony Var-Dumper](https://packagist.org/packages/symfony/var-dumper) >= 3.2
* [Symfony Console](https://packagist.org/packages/symfony/console) >= 3.2

## Installation

Through Composer, obviously:

```
composer require kairichter/logger:7.0.x-dev
```

## Examples

In `vendor/kairichter/logger/examples/` you will find a detailed example of how to set up and use the Logger:

```
cd vendor/kairichter/logger/examples;
php quickstart.php
ls -lah
```

After the execution, a new file `example-YYYY-MM-DD.log` will be located in the new directory` logs`. This contains the log outputs of the file logger. 