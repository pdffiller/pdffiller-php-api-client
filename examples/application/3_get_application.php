<?php
use PDFfiller\OAuth2\Client\Provider\Application;
use \PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

try {
    $response = Application::one($provider, 'fd1880a821c748d4');
    dd($response);
} catch (ResponseException $e) {
    dd($e->getMessage());
}

