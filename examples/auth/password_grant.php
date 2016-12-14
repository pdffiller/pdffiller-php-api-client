<?php

require_once __DIR__.'/../bootstrap/init.php';

use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
use \PDFfiller\OAuth2\Client\Provider\PDFfiller;

$provider = new PDFfiller([
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN')
]);

$provider->getAccessToken(new GrantType(GrantType::PASSWORD_GRANT), [
    'username' => getenv('USER_EMAIL'),
    'password' => getenv('PASSWORD')
]);

dd($provider->queryApiCall('test'));
