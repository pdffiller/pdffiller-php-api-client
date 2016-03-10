<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Uploader;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';

//$documentEntity = new \PDFfiller\OAuth2\Client\Provider\Document($provider);

//$e = $documentEntity->uploadViaMultipart(fopen(__DIR__.'/pdf_open_parameters.pdf', 'r'));

$e = new Uploader($provider);
$document = $e->uploadViaMultipart(fopen(__DIR__ . '/pdf_open_parameters.pdf', 'r'));
dd($document);

