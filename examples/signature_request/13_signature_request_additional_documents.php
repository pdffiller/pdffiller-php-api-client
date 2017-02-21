<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient;


$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$recipient = new SignatureRequestRecipient($provider, ['id' => 612251], 383338);
// Getting the list of additional documents
//$list = $recipient->additionalDocuments();
//dd($list->toArray());

// Getting the additional documents by ID
//$document = $recipient->additionalDocument(747272);
//dd($document);

// Downloading the additional documents by ID
//$document = $recipient->additionalDocument(747272);
//dd($document->download());

do {
    $response = $recipient->downloadAdditionalDocuments();
    sleep(2);
} while(isset($response['message']));

dd($response);