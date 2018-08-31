<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$callback = Callback::one($provider, 34324);
dd($callback);
