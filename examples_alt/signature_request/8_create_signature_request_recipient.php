<?php
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequestRecipient;
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$signatureRequestRecipient = new \PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient($provider, 4240);
//
//$e = $signatureRequestRecipient->create([
//    "email" => "another@user.com",
//    "name" => "AnotherTest User",
//    "access" => "full",
//    "require_photo" => true,
//    "message_subject" => "Email Another Subject",
//    "message_text" => "Another Email text",
//]);

//creating recipient by the signature request
//$signatureRequest = SignatureRequest::one($provider, 129498);
//$recipient = $signatureRequest->createRecipient();

//creating recipient as independent instance
$recipient = new SignatureRequestRecipient($provider, 129498);
$recipient->email = 'new@recipient.com';
$recipient->name = 'New Recipient';
$recipient->access = 'full';
$recipient->require_photo = false;
$recipient->message_subject = 'Email new subject';
$recipient->message_text = 'Hi, its a new message';
$recipient->additional_documents = [];
$recipient->order = 0;
//saving as independent instance
$e = $recipient->save();
//saving by signature request
//$e = $signatureRequest->addRecipient($recipient);
dd($e);
