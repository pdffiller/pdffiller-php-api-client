<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Document;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$documentEntity = new \PDFfiller\OAuth2\Client\Provider\Document($provider);
//
//$e = $documentEntity->itemsList();
$e = Document::all($provider);
dd($e);