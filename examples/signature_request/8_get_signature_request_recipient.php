<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
//$recipient = SignatureRequest::one($provider, 334721)->getRecipient(550632);
$recipient = SignatureRequest::recipient($provider, 334721, 550632);
dd($recipient->toArray());