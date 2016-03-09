<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;

$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';

$e = FillRequest::deleteOne($provider, 53690143);
dd($e);
