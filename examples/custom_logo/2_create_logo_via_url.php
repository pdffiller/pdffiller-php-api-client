<?php
use PDFfiller\OAuth2\Client\Provider\CustomLogo;
use PDFfiller\OAuth2\Client\Provider\Uploader;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider, CustomLogo::class);
$uploader->type = Uploader::TYPE_URL;
$uploader->file = 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/2012_Transit_of_Venus_from_SF.jpg/1280px-2012_Transit_of_Venus_from_SF.jpg';
$logo = $uploader->upload();
dd($logo);
