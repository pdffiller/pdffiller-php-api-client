<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequestForm;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//FillRequestForm::init($provider, 53690143);
$fillRequestFormEntity = new \PDFfiller\OAuth2\Client\Provider\FillRequestForm($provider, 53690143);
//
$e = $fillRequestFormEntity->listItems();
//$e = FillRequestForm::all();
dd($e);
