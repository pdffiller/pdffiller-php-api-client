<?php
use PDFfiller\OAuth2\Client\Provider\Folder;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$folder = Folder::one($provider, 267767);
$e = $folder->remove();
dd($e);