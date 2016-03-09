<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;
use PDFfiller\OAuth2\Client\Provider\Core\Factory;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//FillRequest::init($provider);
//$e = FillRequest::all();
//$factory = new Factory($provider);
$e = FillRequest::all($provider);
dd($e);
