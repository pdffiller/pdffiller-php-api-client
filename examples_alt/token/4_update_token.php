<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Token;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$tokenEntity = new \PDFfiller\OAuth2\Client\Provider\Token($provider);
//
//$e = $tokenEntity->update(123, [
//        'key1' => 'data10',
//        'key2' => 'data20'
//]);

$token = Token::one($provider, 617);

$token->data['key3'] = 'data30';
dd($token->save(false));
