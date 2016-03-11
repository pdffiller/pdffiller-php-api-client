<?php
use PDFfiller\OAuth2\Client\Provider\Alt\SignatureRequestRecipient;

$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$signatureRequestRecipient = new \PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient($provider, 4240);
//
//$e = $signatureRequestRecipient->remind(6120);
$e = SignatureRequestRecipient::one($provider, 129498, 136835)->remind();
dd($e);
