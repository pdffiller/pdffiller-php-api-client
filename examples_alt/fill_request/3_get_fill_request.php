<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;

$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
$factory = new \PDFfiller\OAuth2\Client\Provider\Core\Factory($provider);
$e = $factory->one(FillRequest::class, 53690143);

dd($e);
