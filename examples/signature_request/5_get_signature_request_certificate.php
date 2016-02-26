<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$signatureRequestFormEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);

$e = $signatureRequestFormEntity->certificate('4240');
dd($e);
