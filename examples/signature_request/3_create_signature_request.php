<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);

$e = $signatureRequestEntity->create('20113290', [
    "method" => "sendtoeach",
    "security_pin" => "standard",
    "recipients" => [
        [
            "email" => "test@user.com",
            "name" => "Test User",
            "access" => "full",
            "require_photo" => true,
            "message_subject" => "Email Subject",
            "message_text" => "Email text",
        ]
    ]
]);
dd($e);
