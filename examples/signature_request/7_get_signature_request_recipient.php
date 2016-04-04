<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$recipient = SignatureRequest::one($provider, 9501)->getRecipient(25076);
dd($recipient);
