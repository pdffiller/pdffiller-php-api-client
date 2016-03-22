<?php
use PDFfiller\OAuth2\Client\Provider\Uploader;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = new Uploader($provider);
$document = $e->uploadViaMultipart(fopen(__DIR__ . '/pdf_open_parameters.pdf', 'r'));
dd($document);

