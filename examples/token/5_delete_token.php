<?php
use PDFfiller\OAuth2\Client\Provider\Token;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$token = Token::one($provider, 617);
$e = $token->remove();

dd($e);
