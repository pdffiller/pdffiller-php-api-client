<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = SignatureRequest::one($provider, 129121)->certificate();
dd($e);
