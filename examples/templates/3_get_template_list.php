<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = Template::all($provider, ['order' => 'asc']);
dd($e);
