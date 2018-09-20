<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

//$signatureRequest = SignatureRequest::one($provider, 337730);
//$recipient = $signatureRequest->getRecipient(554689);

$recipients = SignatureRequest::recipients($provider, 111);
$recipient = $recipients[1258405];
$e = $recipient->remind();
dd($e);
