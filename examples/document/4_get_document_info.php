<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$documentEntity = new \aslikeyou\OAuth2\Client\Provider\Document($provider);

$e = $documentEntity->itemInfo('20268658');
dd($e);