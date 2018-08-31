<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$contents = Template::download($provider, 216865300);
dd($contents);
