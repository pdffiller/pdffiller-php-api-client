<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);

$e = $signatureRequestEntity->listItems();
dd($e);
