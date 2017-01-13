# PDFfiller API PHP Client

[![Join the chat at https://gitter.im/pdffiller/pdffiller-php-api-client](https://badges.gitter.im/pdffiller/pdffiller-php-api-client.svg)](https://gitter.im/pdffiller/pdffiller-php-api-client?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[PDFfiller API](https://api.pdffiller.com)

## System Requirements
* PHP >= 5.5.9 but the latest stable version of PHP is recommended;
* `mbstring` extension;
* `intl` extension;

## Install

The library is available on Packagist and can be installed using Composer. This is done by running the following command on a composer installed box:

```
$ composer require pdffiller/pdffiller-php-api-client
```

Most modern frameworks include Composer out of the box, but ensure that the following file is included:

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
Try running 
```
composer self-update 
```
Also you might got something like this:
```
Warning: require_once(../../vendor/autoload.php): failed to open stream: No such file or directory
```
This issue is easily fixed by installing composer dependencies:
```
composer install
```
If you have any problems feel free to contact us:
- On our issues page https://github.com/pdffiller/pdffiller-php-api-client/issues
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
Access token authomatically sets up when it is successfully retrieved, from the given user's credentials (after `PDFfiller\OAuth2\Client\Provider\PDFfiller::getAccessToken($grant_type, $options)` method), according to the example below:

```
<?php
require_once __DIR__.'/vendor/autoload.php';

use \PDFfiller\OAuth2\Client\Provider\Core\GrantType;
use \PDFfiller\OAuth2\Client\Provider\PDFfiller;

$oauthParams = [
    'clientId'       => 'YOUR_CLIENT_ID',
    'clientSecret'   => 'YOUR_CLIENT_SECRET',
    'urlAccessToken' => 'https://api.pdffiller.com/v1/oauth/access_token',
    'urlApiDomain'   => 'https://apidev8.pdffiller.com/v1/'
];

$passwordGrantCredentials = [
    'username' => 'pdffiller_account@example.com',
    'password' => 'some_pass'
];

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = new PDFfiller($oauthParams);

$access_token = $provider->getAccessToken(GrantType::PASSWORD_GRANT, $passwordGrantCredentials);
print_r($access_token);
```

When authorization is successful you can use provider for retrieving, creating, updating or deleting information from your profile.
Retrieving the list of all applications can be done via a static method `PDFfiller\OAuth2\Client\Provider\Core\Model::all(PDFfiller $provider)`:
```
$list = Application::all($provider);
print_r($list);
```
For retrieving information about one application, call static `PDFfiller\OAuth2\Client\Provider\Core\Model::one(PDFfiller $provider, $client_id)`:
```
$application = Application::one($provider, 'app_client_id');
print_r($application);
```

If you want to create a new one, you must create a new Application object with the needed information and save it by using this method `PDFfiller\OAuth2\Client\Provider\Core\Model::save($newRecord = true)`.
```
$application = new Application($provider);

$application->name = 'App name';
$application->description = 'Some application description';
$application->domain = 'http://some.domain.com';
print_r($application->save());
```
If you want to update an instance, you must retrieve an Application object and save it by using this method `PDFfiller\OAuth2\Client\Provider\Core\Model::save($newRecord = true)`.

```
$application = Application::one($provider, '547d2b9c2d3b902a');

$application->name = 'Updated App name';
$application->description = 'Some changed application description';
$result = $application->save();
print_r($result);

```
Updating information is easy by using `PDFfiller\OAuth2\Client\Provider\Core\Model::save()` method:

If you wish to remove an application, use `PDFfiller\OAuth2\Client\Provider\Core\Model::remove()` method
```
$application = Application::one($provider, '547d2b9c2d3b902a');
$result = $application->remove();
print_r($result);
```

All examples with other endpoints are available in the [examples](https://github.com/pdffiller/pdffiller-php-api-client/tree/master/examples) folder

## LICENSE

This software is licensed under the following MIT [license](https://github.com/pdffiller/pdffiller-php-api-client/blob/2.0.0/LICENSE)

## Author
API Team (integrations@pdffiller.com)
