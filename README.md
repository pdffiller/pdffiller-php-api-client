# PDFfiller PHP Client

[![Join the chat at https://gitter.im/pdffiller/pdffiller-php-api-client](https://badges.gitter.im/pdffiller/pdffiller-php-api-client.svg)](https://gitter.im/pdffiller/pdffiller-php-api-client?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[PDFfiller API](https://api.pdffiller.com)
You can sign up for the API [here](https://www.pdffiller.com/en/developers#tab-pricing)

## System Requirements
* PHP >= 7.0 but the latest stable version of PHP is recommended;
* `mbstring` extension;
* `intl` extension;

## Installation
The library is available on Packagist and can be installed using Composer. This is done by running the following command on a composer installed box:

```
$ composer require pdffiller/pdffiller-php-api-client
```

Most modern frameworks include Composer out of the box. However, please ensure that the following file is included:

````php
// Include the Composer autoloader
require 'vendor/autoload.php';
````
### Troubleshooting

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
Also you might encounter the following:
```
Warning: require_once(../../vendor/autoload.php): failed to open stream: No such file or directory
```
This issue is easily fixed by installing composer dependencies:
```
composer install
```

### Quick getting started steps
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

## Authentication
Access tokens automatically initialize when they’re successfully retrieved from the given user's credentials (after PDFfiller\OAuth2\Client\Provider\PDFfiller::getAccessToken($grant_type, $options) method), according to the example below:
````php
<?php
require_once __DIR__.'/vendor/autoload.php';

use \PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
use \PDFfiller\OAuth2\Client\Provider\PDFfiller;

$oauthParams = [
    'clientId'       => 'YOUR_CLIENT_ID',
    'clientSecret'   => 'YOUR_CLIENT_SECRET',
    'urlAccessToken' => 'https://api.pdffiller.com/v2/oauth/token',
    'urlApiDomain'   => 'https://api.pdffiller.com/v2/'
];

$passwordGrantCredentials = [
    'username' => 'pdffiller_account@example.com',
    'password' => 'some_pass'
];

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = new PDFfiller($oauthParams);

$accessToken = $provider->getAccessToken(GrantType::PASSWORD_GRANT, $passwordGrantCredentials);
print_r($accessToken);
````

When your authorization has been completed successfully you can use the provider for retrieving, creating, updating or deleting information from your profile.

## Usage

Use a static method to retrieve a list of all applications:
`PDFfiller\OAuth2\Client\Provider\Core\Model::all(PDFfiller $provider)`
````php
$list = Application::all($provider);
print_r($list);
````
For retrieving information about one application, call static: 
`PDFfiller\OAuth2\Client\Provider\Core\Model::one(PDFfiller $provider, $appClientId)`
````php
$application = Application::one($provider, 'app_client_id');
print_r($application);
````

If you want to create a new application, you must create a new Application object with the necessary information and save it using the following method:
`PDFfiller\OAuth2\Client\Provider\Core\Model::save()`
````php
$application = new Application($provider);

$application->name = 'App name';
$application->description = 'Some application description';
$application->domain = 'http://some.domain.com';
print_r($application->save());
````
If you want to update an instance, you must retrieve an Application object and save it by using the following method:
`PDFfiller\OAuth2\Client\Provider\Core\Model::save()`

````php
$application = Application::one($provider, 'app_client_id');

$application->name = 'Updated App name';
$application->description = 'Some changed application description';
$result = $application->save();
print_r($result);
````
Updating information is easy by using:
`PDFfiller\OAuth2\Client\Provider\Core\Model::save()` method.
If you wish to remove an application, use:
`PDFfiller\OAuth2\Client\Provider\Core\Model::remove()` method
````php
$application = Application::one($provider, 'app_client_id');
$result = $application->remove();
print_r($result);
````
All examples with other endpoints are available in the [examples](https://github.com/pdffiller/pdffiller-php-api-client/tree/master/examples) folder

## Support
If you have any problems feel free to contact us:
* On our issues page https://github.com/pdffiller/pdffiller-php-api-client/issues
* Via chat or phone at our tech site http://developers.pdffiller.com
* Join our Gitter chat room for technical advice https://gitter.im/pdffiller/pdffiller-php-api-client

## License
This software is licensed under the following MIT [license](https://github.com/pdffiller/pdffiller-php-api-client/blob/3.0.0/LICENSE)

## Author
API Team (integrations@pdffiller.com)
