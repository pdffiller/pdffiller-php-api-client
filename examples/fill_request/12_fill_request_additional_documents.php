<?php
use PDFfiller\OAuth2\Client\Provider\FillRequestForm;


$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillRequestForm = new FillRequestForm($provider, 90818604, ['id' => 382897]);
// Getting the list of additional documents
//$list = $fillRequestForm->additionalDocuments();
//dd($list->toArray());

// Getting the additional documents by ID
//$document = $fillRequestForm->additionalDocument(20782);
//dd($document);

// Downloading the additional documents by ID
//$document = $fillRequestForm->additionalDocument(20782);
//dd($document->download());

dd($fillRequestForm->downloadAdditionalDocuments());
