# PDFfiller PHP Client

[![Join the chat at https://gitter.im/pdffiller/pdffiller-php-api-client](https://badges.gitter.im/pdffiller/pdffiller-php-api-client.svg)](https://gitter.im/pdffiller/pdffiller-php-api-client?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[PDFfiller API](https://api.pdffiller.com)
You can sign up for the API [here](https://www.pdffiller.com/en/developers#tab-pricing)

## System Requirements
* PHP >= 5.5.9 but the latest stable version of PHP is recommended;
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
### Uploading a File to PDFfiller
Create a new Uploader object with the necessary information and upload it using:
`PDFfiller\OAuth2\Client\Provider\Uploader::upload()`.
````php
$uploader = new Uploader($provider, Document::class);
$uploader->type = Uploader::TYPE_URL;
$uploader->file = 'http://www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf';
$document = $uploader->upload();
````
### Filling a Document Template
Create a new Fillable Template object, pass values for fields and save using:
`PDFfiller\OAuth2\Client\Provider\FillableTemplate::save()`.
````php
$fields['fillable_fields'] = [
    '*Text_1' => 'Fillable field text', // * - lock field for edit in filled document
    'Number_1' => '2017',
    'Checkbox_1' => '1',
    'Date_1' => '01/13/2017',
];
$fillableTemplate = new FillableTemplate($provider, $fields);
$fillableTemplate->document_id = 11111111;
$result = $fillableTemplate->save();
````
### Creating a LinkToFill Document
Create a new FillRequest object with needed information and save it using:
`PDFfiller\OAuth2\Client\Provider\FillRequest::save()`.
````php
$fillRequestEntity = new FillRequest($provider, [
    'additional_documents' => [
        'name',
        'name2'
    ]
]);

$fillRequestEntity->document_id = 86707463;
$fillRequestEntity->access = "full";
$fillRequestEntity->status = "public";
$fillRequestEntity->email_required = true;
$fillRequestEntity->name_required = true;
$fillRequestEntity->custom_message = "Custom";
$fillRequestEntity->callback_url = "your_application_callback_url";
$fillRequestEntity->notification_emails[] = new NotificationEmail(['name' => 'name', 'email' => 'email@email.com']);
$fillRequestEntity->additional_documents[] = new AdditionalDocument('name1');
$fillRequestEntity->additional_documents[] = new AdditionalDocument('name3');
$fillRequestEntity->enforce_required_fields = true;
$fillRequestEntity->welcome_screen = false;
$fillRequestEntity->notifications = new FillRequestNotifications(FillRequestNotifications::WITH_PDF);
$newFillRequest = $fillRequestEntity->save();
````
### Creating a SendToSign Document
Create a new SignatureRequest object with the necessary information and save it using:
`PDFfiller\OAuth2\Client\Provider\SignatureRequest::save()`.
````php
$signatureRequestEntity = new SignatureRequest($provider);
$signatureRequestEntity->document_id = 11111111;
//$signatureRequestEntity->method = 'sendtoeach';

$signatureRequestEntity->method = new SignatureRequestMethod(SignatureRequestMethod::SEND_TO_GROUP);
$signatureRequestEntity->envelope_name = 'group envelope';
$signatureRequestEntity->sign_in_order = false;

$signatureRequestEntity->security_pin = new SignatureRequestSecurityPin(SignatureRequestSecurityPin::STANDARD);
$signatureRequestEntity->recipients[] = new SignatureRequestRecipient($provider, [
    'email' => 'email@email.com',
    'name' => 'Some user',
    'access' => 'full',
    'require_photo' => false,
    'message_subject' => 'subject',
    'message_text' => 'message',
    'additional_documents' => [
        'doc1'
    ]
]);

$signatureRequestEntity->save();
````
All examples with other endpoints are available in the [examples](https://github.com/pdffiller/pdffiller-php-api-client/tree/master/examples) folder

## Support
If you have any problems feel free to contact us:
* On our issues page https://github.com/pdffiller/pdffiller-php-api-client/issues
* Via chat or phone at our tech site http://developers.pdffiller.com

## License
This software is licensed under the following MIT [license](https://github.com/pdffiller/pdffiller-php-api-client/blob/2.0.0/LICENSE)

## Author
API Team (integrations@pdffiller.com)
