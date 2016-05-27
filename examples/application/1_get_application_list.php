<?php
use PDFfiller\OAuth2\Client\Provider\Application;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$application = new Application($provider);

$e = Application::all($provider);
dd($e);
