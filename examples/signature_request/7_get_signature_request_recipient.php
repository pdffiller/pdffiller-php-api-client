<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = SignatureRequestRecipient::one($provider, 132303, 140861);
dd($e);
