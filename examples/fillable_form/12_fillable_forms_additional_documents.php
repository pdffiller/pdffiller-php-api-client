<?php
use PDFfiller\OAuth2\Client\Provider\FilledForm;
use PDFfiller\OAuth2\Client\Provider\Core\Job;


$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$filledForm = new FilledForm($provider, 219116971, ['filled_form_id' => 1167457]);
// Getting the list of additional documents
//$list = $filledForm->additionalDocuments();
//dd($list->toArray());

// Getting the additional documents by ID
//$document = $filledForm->additionalDocument(109758);
//dd($document);

// Downloading the additional documents by ID
//$document = $filledForm->additionalDocument(109758);
//dd($document->download());

$filledFormAsync = new Job($filledForm);

$filledFormAsync->downloadAdditionalDocuments();

while (!$filledFormAsync->isReady()) {
    sleep(2);
}

dd($filledFormAsync);
