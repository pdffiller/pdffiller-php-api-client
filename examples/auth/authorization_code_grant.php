<?php
require_once __DIR__.'/../bootstrap/init.php';

use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;

$code = 'PLACE_YOUR_CODE_HERE';

$provider = new PDFfiller([
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'redirectUri'    => getenv('REDIRECT_URI'),
]);

$provider->getAccessToken(new GrantType(GrantType::AUTHORIZATION_CODE_GRANT), [
    'code'           => urldecode($code),
]);

dd($provider->queryApiCall('test'));
