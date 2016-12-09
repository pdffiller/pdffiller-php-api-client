<?php

require_once __DIR__.'/../bootstrap/init.php';

use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = (new Examples\ExampleFabric(new GrantType(GrantType::PASSWORD_GRANT), [
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN')
]))->getProvider([
    'username' => getenv('USER_EMAIL'),
    'password' => getenv('PASSWORD')
], false);

dd($provider->queryApiCall('test'));