<?php
use PDFfiller\OAuth2\Client\Provider\Application;
use \PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$application = new Application($provider);

$application->name = 'App name';
$application->description = 'Some application description';
$application->domain = 'http://some.domain.com/callback';
$application->embedded_domain = 'http://some.domain.com';
$application->all_domains = false;

try {
    $response = $application->save();
    dd($response);
} catch (ResponseException $e) {
    dd($e->getMessage());
}

