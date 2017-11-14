<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = new Template($provider,['id'=> 201521530]);
$e = $template->deleteConstructor('76f28ba67d7b25cb4373a86de6073942');
dd($e);