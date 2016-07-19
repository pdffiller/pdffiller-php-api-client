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
## Troubleshooting

If you have the following error:
```
[RuntimeException]
 Could not load package pdffiller/pdffiller-php-api-client in http://packagi
 st.org: [UnexpectedValueException] Could not parse version constraint ^5.2:
  Invalid version string "^5.2"


 [UnexpectedValueException]
 Could not parse version constraint ^5.2: Invalid version string "^5.2"
```
Try run 
```
composer self-update 
```
It should help. If you have any problems feel free to contact us:
- by issues page https://github.com/pdffiller/pdffiller-php-api-client/issues
- via chat or phone at our tech site http://developers.pdffiller.com/

### Validation package problem

We are using validation package https://github.com/illuminate/validation at 5.2 version
Usually it's ok but if you have laravel 5.0 project it can became a problem. 
If you have such problem, please contact us and we will fix it.

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
