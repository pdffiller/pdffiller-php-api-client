<?php
use PDFfiller\OAuth2\Client\Provider\CustomLogo;
use PDFfiller\OAuth2\Client\Provider\Uploader;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider, CustomLogo::class);
$uploader->type = Uploader::TYPE_URL;
$uploader->file = 'http://design.ubuntu.com/wp-content/uploads/ubuntu-logo32.png';
$logo = $uploader->upload();
dd($logo);