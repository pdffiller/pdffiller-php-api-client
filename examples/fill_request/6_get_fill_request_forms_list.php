<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = FillRequest::one($provider, 90818604)->forms();
dd($e);
