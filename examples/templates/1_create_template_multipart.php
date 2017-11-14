<?php
use PDFfiller\OAuth2\Client\Provider\Uploader;
use \PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$uploader = new Uploader($provider, Template::class);
$uploader->type = Uploader::TYPE_MULTIPART;
$uploader->file = __DIR__ . '/pdf_open_parameters.pdf';

$template = $uploader->upload();
dd($template);