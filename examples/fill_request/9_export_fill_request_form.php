<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$fillRequestFormEntity = new \aslikeyou\OAuth2\Client\Provider\FillRequestForm($provider, 20113290);

$e = $fillRequestFormEntity->export(1);
dd($e);
