<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$fillRequestFormEntity = new \PDFfiller\OAuth2\Client\Provider\FillRequestForm($provider, 20113290);

$e = $fillRequestFormEntity->listItems();
dd($e);
