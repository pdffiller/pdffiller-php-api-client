# Pdffiller PHP Client

API client for [Pdffiller API](https://api.pdffiller.com).

System Requirements
* PHP >= 5.5.9 but the latest stable version of PHP is recommended;
* `mbstring` extension;
* `intl` extension;

## Install

The library is available on Packagist and should be installed using Composer. This can be done by running the following command on a composer installed box:

```
$ composer require pdffiller/pdffiller-php-api-client
```

Most modern frameworks will include Composer out of the box, but ensure the following file is included:

```php
// Include the Composer autoloader
require 'vendor/autoload.php';
```

## Quick getting started steps
Install required libraries using composer
```
cd pdffiller-php-api-client/
composer install
```

Edit .env file in examples dir to set client_id and client_secret
```
cd examples/ 
cp .env.example .env
vi .env
```

Run any example
```
cd signature_request/
php 1_get_signature_request_list.php
```

## Usage

Usage examples available at [examples](https://github.com/pdffiller/pdffiller-php-api-client/tree/master/examples) dir

## LICENSE

This software is licensed under following MIT [license](https://github.com/pdffiller/pdffiller-php-api-client/blob/master/LICENSE)

## Author
Alex Pekhota (pekhota.alex@pdffiller.com)
