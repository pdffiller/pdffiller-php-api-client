<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Uploader;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$documentEntity = new \PDFfiller\OAuth2\Client\Provider\Document($provider);
//
//$e = $documentEntity->uploadViaUrl('www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf');

$uploader = new Uploader($provider);
$document = $uploader->uploadViaUrl('www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf');
dd($document);