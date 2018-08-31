<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;
use PDFfiller\OAuth2\Client\Provider\Enums\FillRequestNotifications;
use PDFfiller\OAuth2\Client\Provider\DTO\NotificationEmail;
use PDFfiller\OAuth2\Client\Provider\DTO\AdditionalDocument;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$fillRequestEntity = new FillableForm($provider);

$fillRequestEntity->document_id = 23232323;
$fillRequestEntity->access = "full";
$fillRequestEntity->status = "public";
$fillRequestEntity->email_required = true;
$fillRequestEntity->name_required = true;
$fillRequestEntity->custom_message = "Custom";
$fillRequestEntity->callback_url = "http://testhostexample.com";
$fillRequestEntity->notification_emails[] = new NotificationEmail(['name' => 'name', 'email' => 'email@email.com']);
$fillRequestEntity->additional_documents = ['test', 'test22'];
$fillRequestEntity->enforce_required_fields = true;
$fillRequestEntity->welcome_screen = false;
$fillRequestEntity->notifications = new FillRequestNotifications(FillRequestNotifications::WITH_PDF);

$e = $fillRequestEntity->save();

dd($e);
