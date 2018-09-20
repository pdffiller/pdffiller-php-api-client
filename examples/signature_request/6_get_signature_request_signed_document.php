<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$signatureRequest = SignatureRequest::one($provider, 3454);
//$sr = (new SignatureRequest($provider, ['id' => 3434]));
$e = $signatureRequest->signedDocument();
dd($e);
