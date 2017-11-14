<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = Template::one($provider, 201526279);
$e = $template->remove();
dd($e);