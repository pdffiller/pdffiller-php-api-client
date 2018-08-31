<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient;
use PDFfiller\OAuth2\Client\Provider\Core\Job;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$recipient = new SignatureRequestRecipient($provider, ['id' => 1308788], 807461);
// Getting the list of additional documents
//$list = $recipient->additionalDocuments();
//dd($list->toArray());

// Getting the additional documents by ID
//$document = $recipient->additionalDocument(1770196);
//dd($document);

// Downloading the additional documents by ID
$document = $recipient->additionalDocument(1770196);
dd($document->download());

$recipientAsync = new Job($recipient);
$recipientAsync->downloadAdditionalDocuments();

while (!$recipientAsync->isReady()) {
    sleep(2);
}

dd($recipientAsync);