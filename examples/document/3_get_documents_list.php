<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = Document::all($provider);
dd($e);