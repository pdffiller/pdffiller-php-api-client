<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$signatureRequestRecipient = new \aslikeyou\OAuth2\Client\Provider\SignatureRequestRecipient($provider, 4240);

$e = $signatureRequestRecipient->info(6120);
dd($e);
