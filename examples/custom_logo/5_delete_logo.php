<?php
use PDFfiller\OAuth2\Client\Provider\CustomLogo;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = CustomLogo::deleteOne($provider, 34543);
dd($e);
