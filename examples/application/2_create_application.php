<?php
use PDFfiller\OAuth2\Client\Provider\Application;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$application = new Application($provider);

$application->name = 'App name';
$application->description = 'Some application description';
$application->domain = 'http://some.domain.com';
dd($application->save());
