<?php
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);
//$e = $signatureRequestEntity->info('4240');

$e = SignatureRequest::one($provider, 129121);
dd($e);
