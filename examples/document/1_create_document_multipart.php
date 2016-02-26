<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';

$documentEntity = new \PDFfiller\OAuth2\Client\Provider\Document($provider);

$e = $documentEntity->uploadViaMultipart(fopen(__DIR__.'/pdf_open_parameters.pdf', 'r'));
dd($e);

