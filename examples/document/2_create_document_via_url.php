<?php
use PDFfiller\OAuth2\Client\Provider\Uploader;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider);
$document = $uploader->uploadViaUrl('www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf');
dd($document);