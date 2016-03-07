<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;

$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
FillRequest::init($provider);
$e = FillRequest::one(53690143);

dd($e);
