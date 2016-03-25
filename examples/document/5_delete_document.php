<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$document = Document::one($provider, 20310993);
$e = $document->remove();
dd($e);