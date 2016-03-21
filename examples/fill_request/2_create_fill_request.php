<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$fillRequestEntity = new FillRequest($provider);
$fillRequestEntity->document_id = 53690143;
$fillRequestEntity->access = "full";
$fillRequestEntity->status = "public";
$fillRequestEntity->email_required = true;
$fillRequestEntity->name_required = true;
$fillRequestEntity->custom_message = "Custom";
$fillRequestEntity->notification_emails = [['name' => 'name', 'email' => 'email@email.com']];

$e = $fillRequestEntity->save();

dd($e);
