<?php
use PDFfiller\OAuth2\Client\Provider\Uploader;
use PDFfiller\OAuth2\Client\Provider\CustomLogo;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider, CustomLogo::class);
$uploader->type = Uploader::TYPE_MULTIPART;
$uploader->file = __DIR__ . '/test.jpg';
$document = $uploader->upload();
dd($document);

