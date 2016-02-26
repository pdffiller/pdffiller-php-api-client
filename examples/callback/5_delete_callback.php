<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$callbackEntity = new \PDFfiller\OAuth2\Client\Provider\Callback($provider);

$e = $callbackEntity->delete(123);
dd($e);
