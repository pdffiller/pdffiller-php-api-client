<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;
use PDFfiller\OAuth2\Client\Provider\DTO\AdditionalDocument;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillRequestEntity = FillRequest::one($provider, 86408748);
//dd($fillRequestEntity->toArray());
//dd($fillRequestEntity->toArray(['except' => ['additional_documents', 'bla']]));
$fillRequestEntity->custom_message = "Updated custom message";
$fillRequestEntity->additional_documents[] = new AdditionalDocument(['name' => 'add_doc']);

$e = $fillRequestEntity->save(false);
dd($e);
