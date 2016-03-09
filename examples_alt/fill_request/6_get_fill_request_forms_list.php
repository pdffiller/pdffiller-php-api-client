<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequestForm;
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//FillRequestForm::init($provider, 53690143);
//$fillRequestFormEntity = new \PDFfiller\OAuth2\Client\Provider\FillRequestForm($provider, 53690143);
//
//$e = $fillRequestFormEntity->listItems();
$e = FillRequest::one($provider, 53690143)->forms();
dd($e);
