<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$doc = Document::download($provider, 123);
dd($e);