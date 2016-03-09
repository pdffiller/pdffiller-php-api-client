<?php
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$signatureRequestFormEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);
//
//$e = $signatureRequestFormEntity->certificate('4240');
$e = SignatureRequest::one($provider, 129121)->certificate();
dd($e);
