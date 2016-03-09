<?php
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);

$e = SignatureRequest::all($provider);
dd($e);
