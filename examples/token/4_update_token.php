<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$tokenEntity = new \PDFfiller\OAuth2\Client\Provider\Token($provider);

$e = $tokenEntity->update(123, [
        'key1' => 'data10',
        'key2' => 'data20'
]);
dd($e);
