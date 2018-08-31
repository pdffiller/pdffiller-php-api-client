<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = new Template($provider,['id'=> 216865300]);
$e = $template->deleteConstructor('be5db41a062fe77fa95a6a4703713238');
dd($e);
