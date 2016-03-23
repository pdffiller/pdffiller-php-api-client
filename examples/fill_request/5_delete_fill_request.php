<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = FillRequest::deleteOne($provider, 53690143);
dd($e);
