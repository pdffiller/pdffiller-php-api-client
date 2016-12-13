<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
//$recipient = SignatureRequest::one($provider, 129121)->getRecipients();
$recipient = SignatureRequest::recipients($provider, 129121);
dd($recipient->toArray());
