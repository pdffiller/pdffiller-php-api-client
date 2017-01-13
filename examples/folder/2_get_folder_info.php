<?php
use PDFfiller\OAuth2\Client\Provider\Folder;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = Folder::one($provider, 267766);
dd($e);