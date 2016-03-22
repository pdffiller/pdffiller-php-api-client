<?php
use PDFfiller\OAuth2\Client\Provider\Token;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$token = new Token($provider);

$token->data = [
    'key1' => 'value1',
    'key2' => 'value2'
];
dd($token->save());
