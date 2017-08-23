# Logger - Allgemeiner Logger basierend auf dem PSR-3 Logger-Interface

## Wiki

Alle Informationen zur Verwendung des Loggers findest du im Wiki unter: https://github.com/kairichter/logger/wiki

## Voraussetzungen

* PHP >= 7.1
* PSR-Log >= 1.0 (https://packagist.org/packages/psr/log)
* Monolog >= 1.22 (https://packagist.org/packages/monolog/monolog)
* Symfony Var-Dumper >= 3.2 (https://packagist.org/packages/symfony/var-dumper)
* Symfony Console >= 3.2 (https://packagist.org/packages/symfony/console)

## Weitere Versionen

Du findest eine für andere PHP-Versionen angepasste Version des Loggers in folgenden Branches:

* [PHP 7.0](https://github.com/kairichter/logger/tree/7.0)
* [PHP 5.6](https://github.com/kairichter/logger/tree/5.6)

## Installation

Wechsele in dein Projekt-Verzeichnis und lege eine `composer.json` mit folgendem Inhalt an:
```json
{
  "name": "vendor/mysupercoolproject",
  "description": "This is my super cool description",
  "license": "GPL-2.0+",
  "require": {
    "kairichter/logger": "dev-master"
  }
}
```

Nach dem Speichern der Datei, lässt du Composer die Arbeit tun. Composer wird dabei die oben genannten Voraussetzungen prüfen und installieren.

```
composer install
```

In `vendor/kairichter/logger/examples/` findest du ein detailiertes Beispiel für die Einrichtung und Verwendung des Loggers:

```
cd vendor/kairichter/logger/examples;
php quickstart.php
ls -lah
```

Nach der Ausführung wird eine neue Datei `example-YYYY-MM-DD.log` in dem neuen Verzeichnis `logs` liegen. Diese enthällt die Log-Ausgaben des File-Loggers. Gerne kannst du ein wenig mit der Konfiguration in der `examples/config.php` spielen und auch mal das Slack-Log aktivieren, indem du den Token usw. angibst. 

