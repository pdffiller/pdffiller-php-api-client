<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//FillRequest::init($provider);
//$e = FillRequest::all();
$e = FillRequest::all($provider);
dd($e);
