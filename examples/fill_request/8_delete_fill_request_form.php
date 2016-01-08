<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$fillRequestFormEntity = new \aslikeyou\OAuth2\Client\Provider\FillRequestForm($provider, 20113290);

$e = $fillRequestFormEntity->delete(1);
dd($e);
