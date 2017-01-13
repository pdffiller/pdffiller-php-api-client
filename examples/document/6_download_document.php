<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$contents = Document::download($provider, 123);
dd($contents);