<?php

require_once __DIR__.'/../bootstrap/init.php';

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = Examples\ExampleFabric::getProvider(Examples\ExampleFabric::CLIENT_CRIDENTIALS_GRANT, [
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'code'           => getenv('CODE'),
    'redirectUri'    => getenv('REDIRECT_URI')
]);

dd($provider->queryApiCall('test'));