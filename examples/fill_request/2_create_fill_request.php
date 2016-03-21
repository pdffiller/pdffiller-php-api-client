<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$fillRequestEntity = new \PDFfiller\OAuth2\Client\Provider\FillRequest($provider);

$e = $fillRequestEntity->create(20113290, ["access" => "full", "status" => "public", "email_required" => true,
    "name_required" => true, "custom_message" => "Custom string to show for a user",
    "required_fields" => true, "custom_logo" => "https://www.pdffiller.com/img/logo-pdffiller.svg",
    "notification_emails" => [
        [ "name" => "Test User", "email" => "test@user.com" ],
        [ "name" => "Another Testuser", "email" => "another@user.com" ]
    ]
]);
dd($e);
