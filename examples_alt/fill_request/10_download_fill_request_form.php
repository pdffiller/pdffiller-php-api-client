<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequestForm;
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$fillRequestFormEntity = new \PDFfiller\OAuth2\Client\Provider\FillRequestForm($provider, 20113290);
//
//$e = $fillRequestFormEntity->download(1);
FillRequest::one($provider, 53690143)->form(1)->download();
dd($e);
