<?php

require_once __DIR__.'/../bootstrap/init.php';

dd(md5(getenv('USER_ID').getenv('USER_EMAIL').md5(getenv('PASSWORD'))));
/** @var \aslikeyou\OAuth2\Client\Provider\Pdffiller $provider */
$provider = Examples\ExampleFabric::getProvider(Examples\ExampleFabric::INTERNAL_GRANT, [
    'clientId'       => getenv('PASSWORD_GRANT_CLIENT_ID'),
    'clientSecret'   => getenv('PASSWORD_GRANT_CLIENT_SECRET'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN')
], [
    'username' => getenv('USER_EMAIL'),
    'password' => getenv('PASSWORD_INTERNAL')
]);



dd($provider->queryApiCall('test'));