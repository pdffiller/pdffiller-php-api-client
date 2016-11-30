<?php

require_once __DIR__.'/../bootstrap/init.php';

$code = 'PLACE_YOUR_AUTHORIZATION_CODE_HERE';

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = Examples\ExampleFabric::getProvider(Examples\ExampleFabric::AUTHORIZATION_CODE_GRANT, [
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'redirectUri'    => getenv('REDIRECT_URI'),
], [
    'code'           => $code,
]);

dd($provider->queryApiCall('test'));