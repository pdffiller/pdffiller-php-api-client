<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
error_reporting(32000);
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);

$e = SignatureRequest::all($provider);
dd($e);
