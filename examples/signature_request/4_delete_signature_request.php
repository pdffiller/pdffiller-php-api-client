<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
$provider = require_once __DIR__ . '/../../examples/bootstrap/initWithFabric.php';
$e = SignatureRequest::deleteOne($provider, 333);
dd($e);