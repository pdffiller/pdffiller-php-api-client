<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$signatureRequestRecipient = new \aslikeyou\OAuth2\Client\Provider\SignatureRequestRecipient($provider, 4240);

$e = $signatureRequestRecipient->create([
    "email" => "another@user.com",
    "name" => "AnotherTest User",
    "access" => "full",
    "require_photo" => true,
    "message_subject" => "Email Another Subject",
    "message_text" => "Another Email text",
]);
dd($e);
