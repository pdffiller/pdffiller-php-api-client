<?php

require_once __DIR__.'/../bootstrap/init.php';

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = Examples\ExampleFabric::getProvider(Examples\ExampleFabric::PASSWORD_GRANT, [
    'clientId'       => getenv('PASSWORD_GRANT_CLIENT_ID'),
    'clientSecret'   => getenv('PASSWORD_GRANT_CLIENT_SECRET'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN')
], [
    'username' => getenv('USER_EMAIL'),
    'password' => getenv('PASSWORD')
]);

dd($provider->queryApiCall('test'));