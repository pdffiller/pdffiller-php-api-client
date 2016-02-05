<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$callbackEntity = new \aslikeyou\OAuth2\Client\Provider\Callback($provider);

$e = $callbackEntity->info('123');
dd($e);
