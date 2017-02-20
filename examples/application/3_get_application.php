<?php
use PDFfiller\OAuth2\Client\Provider\Application;
use \PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

try {
    $application = Application::one($provider, '12c45b2637b93663');
    dd($application->toArray());
} catch (ResponseException $e) {
    dd($e->getMessage());
}

