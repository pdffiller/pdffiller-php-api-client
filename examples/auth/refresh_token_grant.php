<?php
use Examples\ExampleFabric;
use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;

require_once __DIR__ . '/../bootstrap/init.php';

$params = [
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN')
];
/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = (new ExampleFabric(new GrantType(GrantType::PASSWORD_GRANT), $params))->getProvider([
    'username' => getenv('USER_EMAIL'),
    'password' => getenv('PASSWORD')
], false);

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = (new ExampleFabric(new GrantType(GrantType::REFRESH_TOKEN_GRANT), $params))->getProvider([
    'refresh_token' => $provider->getAccessToken()->getRefreshToken()
], false);

dd($provider->queryApiCall('test'));