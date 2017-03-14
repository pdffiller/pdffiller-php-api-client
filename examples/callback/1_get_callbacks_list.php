<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$list = Callback::all($provider);
dd($list->toArray());
