<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$callbackEntity = new \aslikeyou\OAuth2\Client\Provider\Callback($provider);

$e = $callbackEntity->listItems();
dd($e);
