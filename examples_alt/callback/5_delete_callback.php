<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Callback;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$callbackEntity = new \PDFfiller\OAuth2\Client\Provider\Callback($provider);
//
//$e = $callbackEntity->delete(123);
$e = Callback::deleteOne($provider, 684);
dd($e);
