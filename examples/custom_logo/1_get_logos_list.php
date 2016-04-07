<?php
use PDFfiller\OAuth2\Client\Provider\CustomLogo;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = CustomLogo::all($provider);
dd($e);
