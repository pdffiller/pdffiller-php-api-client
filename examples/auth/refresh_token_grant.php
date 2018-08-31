<?php

require_once __DIR__.'/../bootstrap/init.php';

use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
use \PDFfiller\OAuth2\Client\Provider\PDFfiller;

$provider = new PDFfiller([
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN'),
]);

$provider->getAccessToken(new GrantType(GrantType::PASSWORD_GRANT), [
    'username' => getenv('USER_EMAIL'),
    'password' => getenv('PASSWORD'),
    'redirect_uri' => getenv('REDIRECT_URI'),
]);

$token = $provider->issueAccessToken(new GrantType(GrantType::REFRESH_TOKEN_GRANT), [
    'refresh_token' => $provider->getAccessToken()->getRefreshToken(),
    'redirect_uri' => getenv('REDIRECT_URI'),
]);

$provider->setAccessToken($token);

dd($provider->queryApiCall('test'));
