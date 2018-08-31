<?php
use PDFfiller\OAuth2\Client\Provider\Application;
use \PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

try {
    $application = Application::one($provider, 'a5aa457bef001ce6');
    dd($application->toArray());
} catch (ResponseException $e) {
    dd($e->getMessage());
}

