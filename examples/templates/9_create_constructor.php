<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = Template::one($provider, 218763307);
$e = $template->createConstructor(
    [
        'callback_url' => 'http://google.com',
        'expire' => 2,
    ]
);
dd($e);
