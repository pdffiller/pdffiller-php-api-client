<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
$params = [
    "document_id" => 53690143,
    "access" => "full",
    "status" => "public",
    "email_required" => true,
    "name_required" => true,
    "custom_message" => "Custom string to show for a user",
    "notification_emails" => [
        [ "name" => "Test User", "email" => "test@user.com" ],
    ]
];
$fillRequestEntity = FillRequest::one($provider, 53690143);
$fillRequestEntity->custom_message = "New custom message";
$e = $fillRequestEntity->save(false);
dd($e);
