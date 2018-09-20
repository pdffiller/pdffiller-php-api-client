<?php
use PDFfiller\OAuth2\Client\Provider\Token;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$token = Token::one($provider, 3329);

$token->data['key3'] = 'data30';
dd($token->save());
