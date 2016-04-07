<?php
use PDFfiller\OAuth2\Client\Provider\Uploader;
use \PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider, Document::class);
$uploader->type = Uploader::TYPE_URL;
$uploader->file = 'www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf';
$document = $uploader->upload();
dd($document);