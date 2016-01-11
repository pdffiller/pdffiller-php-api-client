<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$fillRequestEntity = new \aslikeyou\OAuth2\Client\Provider\FillRequest($provider);

$e = $fillRequestEntity->info('20113290');
dd($e);
