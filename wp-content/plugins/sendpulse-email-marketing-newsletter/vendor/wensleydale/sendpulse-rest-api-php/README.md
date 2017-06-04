# sendpulse-rest-api
A simple composer enabled version of the [SendPulse REST client library](https://github.com/sendpulse/sendpulse-rest-api-php) and example for PHP.

### Installing via Composer

The recommended way to install the library is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add sendpulse-rest-api as a dependency
php composer.phar require wensleydale/sendpulse-rest-api:dev-master
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```