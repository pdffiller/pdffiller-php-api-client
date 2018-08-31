<?php
use PDFfiller\OAuth2\Client\Provider\CustomLogo;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = CustomLogo::one($provider, 4534);
dd($e);
