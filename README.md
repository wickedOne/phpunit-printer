# PhpStorm printer

prints additional PhpStorm editor url in PHPUnit cli output.

## installation
```bash
$ composer require --dev wickedone/phpunit-printer
```

### command line usage:
specify this printer on the command line:

```bash
$ php vendor/bin/phpunit --printer='WickedOne\PHPUnitPrinter\PhpStormPrinter' src/
```

### phpunit xml configuration
specify this printer in your ``phpunit.xml.dist``:
```xml
<?xml version="1.0" encoding="UTF-8"?>

<!-- https://phpunit.readthedocs.io/en/latest/configuration.html -->
<phpunit
        ...
        printerClass="WickedOne\PHPUnitPrinter\PhpStormPrinter"
        ...
        >
```