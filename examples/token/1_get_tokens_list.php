<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$tokenEntity = new \PDFfiller\OAuth2\Client\Provider\Token($provider);

$e = $tokenEntity->listItems();
dd($e);
