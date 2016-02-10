<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$tokenEntity = new \aslikeyou\OAuth2\Client\Provider\Token($provider);

$e = $tokenEntity->info('123');
dd($e);
