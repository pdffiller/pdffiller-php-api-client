<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;
use PDFfiller\OAuth2\Client\Provider\Enums\FillRequestNotifications;
use PDFfiller\OAuth2\Client\Provider\DTO\NotificationEmail;
use PDFfiller\OAuth2\Client\Provider\DTO\AdditionalDocument;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$fillRequestEntity = new FillRequest($provider, [
    'additional_documents' => [
        'name',
        'name2'
    ]
]);

$fillRequestEntity->document_id = 86707463;
$fillRequestEntity->access = "full";
$fillRequestEntity->status = "public";
$fillRequestEntity->email_required = true;
$fillRequestEntity->name_required = true;
$fillRequestEntity->custom_message = "Custom";
$fillRequestEntity->callback_url = "http://apicallbacks.pdffiller.com/handle?hash=l2f_php_client";
$fillRequestEntity->notification_emails[] = new NotificationEmail(['name' => 'name', 'email' => 'email@email.com']);
$fillRequestEntity->additional_documents[] = new AdditionalDocument('name1');
$fillRequestEntity->additional_documents[] = new AdditionalDocument('name3');
//$fillRequestEntity->additional_documents = [new AdditionalDocument(['name' => 'name1']), new AdditionalDocument(['name' => 'name 1'])];
$fillRequestEntity->enforce_required_fields = true;
$fillRequestEntity->welcome_screen = false;
$fillRequestEntity->notifications = new FillRequestNotifications(FillRequestNotifications::WITH_PDF);

$e = $fillRequestEntity->save();

dd($e);
