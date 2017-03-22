<?php
use PDFfiller\OAuth2\Client\Provider\Application;
use \PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$application = new Application($provider);

try {
    $response = Application::all($provider);
    dd($response->toArray());
} catch (ResponseException $e) {
    dd($e);
}
