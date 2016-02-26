<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$documentEntity = new \PDFfiller\OAuth2\Client\Provider\Document($provider);

$e = $documentEntity->uploadViaUrl('www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf');
dd($e);