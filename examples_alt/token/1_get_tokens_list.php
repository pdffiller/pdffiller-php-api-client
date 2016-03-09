<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Token;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
$tokenEntity = new \PDFfiller\OAuth2\Client\Provider\Token($provider);

//$e = $tokenEntity->listItems();
$e = Token::all($provider);
dd($e);
