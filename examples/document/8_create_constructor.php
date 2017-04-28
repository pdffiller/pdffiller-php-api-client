<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$document = Document::one($provider, 97917917);
$e = $document->createConstructor();

dd($e);