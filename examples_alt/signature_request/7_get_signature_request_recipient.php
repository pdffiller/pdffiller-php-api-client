<?php
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequestRecipient;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$signatureRequestRecipient = new \PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient($provider, 4240);
//
//$e = $signatureRequestRecipient->info(6120);
$e = SignatureRequestRecipient::one($provider, 129121, 136835);
dd($e);
