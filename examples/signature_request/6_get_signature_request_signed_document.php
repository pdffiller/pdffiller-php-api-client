<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$sr = SignatureRequest::one($provider, 129121);
//$sr = (new SignatureRequest($provider, ['id' => 129121]));
$e = $sr->signedDocument();
dd($e);
