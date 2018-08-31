<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = Callback::deleteOne($provider, 34545);
dd($e);
