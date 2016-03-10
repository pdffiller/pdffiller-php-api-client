<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Document;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$documentEntity = new \PDFfiller\OAuth2\Client\Provider\Document($provider);
//
//$e = $documentEntity->itemInfo('20268658');
$e = Document::one($provider, 53690143);
dd($e);