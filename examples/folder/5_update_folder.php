<?php
use PDFfiller\OAuth2\Client\Provider\Folder;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$folder = Folder::one($provider, 4324);
$folder->name = "Update folder name 1";

$e = $folder->save();
dd($e);