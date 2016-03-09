<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Token;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$tokenEntity = new \PDFfiller\OAuth2\Client\Provider\Token($provider);
//
//$e = $tokenEntity->info('123');
$e = Token::one($provider, 617);
dd($e);
