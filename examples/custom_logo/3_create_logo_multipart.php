<?php
use PDFfiller\OAuth2\Client\Provider\Uploader;
use PDFfiller\OAuth2\Client\Provider\CustomLogo;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider, CustomLogo::class);
$uploader->type = Uploader::TYPE_MULTIPART;
$uploader->file = fopen(__DIR__ . '/test.jpg', 'r');
$document = $uploader->upload();
dd($document);

