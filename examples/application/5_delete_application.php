<?php
use PDFfiller\OAuth2\Client\Provider\Application;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$application = Application::one($provider, '547d2b9c2d3b902a');

$e = $application->remove();
dd($e);
