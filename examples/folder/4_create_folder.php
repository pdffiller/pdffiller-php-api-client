<?php
use PDFfiller\OAuth2\Client\Provider\Folder;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$folder = new Folder($provider);
$folder->name = "New folder";
$e = $folder->save();
dd($e);