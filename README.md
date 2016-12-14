# PDFfiller API PHP Client

[PDFfiller API](https://api.pdffiller.com)

## System Requirements
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

## Quick getting started steps
Install required libraries using composer
```
cd pdffiller-php-api-client/
composer install
```

Edit `.env` file in examples directory setting client_id, client_secret, username and password 
(for authorization via `password_grant`)
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

Usage examples available at [examples](https://github.com/pdffiller/pdffiller-php-api-client/tree/2.0.0/examples) dir

## LICENSE

This software is licensed under following MIT [license](https://github.com/pdffiller/pdffiller-php-api-client/blob/2.0.0/LICENSE)

## Author
API Team (integrations@pdffiller.com)
