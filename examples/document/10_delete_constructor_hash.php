<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$document = new Document($provider,['id'=>97917917]);
$e = $document->deleteConstructor('bb4cfc70872166a83db1367e8e54552e');

dd($e);