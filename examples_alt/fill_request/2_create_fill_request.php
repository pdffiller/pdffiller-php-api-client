<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;

$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
FillRequest::init($provider);
$params = [
    "document_id" => 53690143,
    "access" => "full",
    "status" => "public",
    "email_required" => true,
    "name_required" => true,
    "custom_message" => "Custom string to show for a user",
    "notification_emails" => [
        [ "name" => "Test User", "email" => "test@user.com" ],
        [ "name" => "Another Testuser", "email" => "another@user.com" ]
    ]
];
$fillRequestEntity = new FillRequest($params);
$fillRequestEntity->save();
//dd($fillRequestEntity->toArray());
//
//$e = $fillRequestEntity->create(53690157, ["access" => "full", "status" => "public", "email_required" => true,
//    "name_required" => true, "custom_message" => "Custom string to show for a user",
//    "notification_emails" => [
//        [ "name" => "Test User", "email" => "test@user.com" ],
//        [ "name" => "Another Testuser", "email" => "another@user.com" ]
//    ]
//]);
dd($fillRequestEntity);
