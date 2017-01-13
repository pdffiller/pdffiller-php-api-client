<?php
use PDFfiller\OAuth2\Client\Provider\Application;
use \PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

try {
    $application = Application::one($provider, 'fd1880a821c748d4');

    $application->name = 'Updated App name';
    $application->description = 'Some changed application description';
    $application->all_domains = true;

    $response = $application->save();
    dd($response);
} catch (ResponseException $e) {
    dd($e->getMessage());
}

