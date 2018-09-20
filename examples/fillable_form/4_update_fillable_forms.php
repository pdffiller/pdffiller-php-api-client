<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;
use PDFfiller\OAuth2\Client\Provider\DTO\AdditionalDocument;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillableFormEntity = FillableForm::one($provider, 165825280);
//dd($fillableFormEntity->toArray());
$fillableFormEntity->custom_message = "Updated custom message for example";
//$fillableFormEntity->additional_documents[] = ['add_doc2'];

$e = $fillableFormEntity->save();
dd($e);
