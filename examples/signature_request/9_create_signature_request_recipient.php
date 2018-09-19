<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient;
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

//creating recipient by the signature request
$signatureRequest = SignatureRequest::one($provider, 45435);
$recipient = $signatureRequest->createRecipient();

//creating recipient as independent instance
//$recipient = new SignatureRequestRecipient($provider, 334721);

//filling recipient fields
$recipient->email = 'email.s@email.test';
$recipient->name = 'New Recipient';
$recipient->access = 'full';
$recipient->require_photo = false;
$recipient->message_subject = 'Email new subject';
$recipient->message_text = 'Hi, its a new message';
$recipient->additional_documents = [];
$recipient->order = 1;
//saving as independent instance
$e = $signatureRequest->addRecipient($recipient);
dd($e->toArray(), $signatureRequest->recipients, $e == $recipient);
