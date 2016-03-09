<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Token;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$tokenEntity = new \PDFfiller\OAuth2\Client\Provider\Token($provider);
$token = new Token($provider);
//$e = $tokenEntity->create([
//    'key1' => 'value1',
//    'key2' => 'value2'
//]);

$token->data = [
    'key1' => 'value1',
    'key2' => 'value2'
];
dd($token->save());
