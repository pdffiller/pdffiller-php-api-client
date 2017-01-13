<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;
use PDFfiller\OAuth2\Client\Provider\DTO\AdditionalDocument;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillRequestEntity = FillRequest::one($provider, 86408748);
$fillRequestEntity->custom_message = "Updated custom message";
$fillRequestEntity->additional_documents[] = new AdditionalDocument('add_doc2');

$e = $fillRequestEntity->save();
dd($e);
