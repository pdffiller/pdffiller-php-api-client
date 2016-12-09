<?php
use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;

require_once __DIR__.'/../bootstrap/init.php';

$code = 'xKBlvLiudNtaY0LDGcBIHahWzRHXF2QXJIILh835';

/** @var \PDFfiller\OAuth2\Client\Provider\PDFfiller $provider */
$provider = (new Examples\ExampleFabric(new GrantType(GrantType::AUTHORIZATION_CODE_GRANT), [
    'clientId'       => getenv('CLIENT_ID'),
    'clientSecret'   => getenv('CLIENT_SECRET'),
    'urlApiDomain'   => getenv('URL_API_DOMAIN'),
    'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),
    'redirectUri'    => getenv('REDIRECT_URI'),
]))->getProvider([
    'code'           => $code,
], false);

dd($provider->queryApiCall('test'));