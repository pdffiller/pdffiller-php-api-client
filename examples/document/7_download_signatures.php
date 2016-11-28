<?php
use PDFfiller\OAuth2\Client\Provider\Document;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$signatures = Document::downloadSignatures($provider, 20608938);
$fp = fopen('signatures.zip', 'w');
fwrite($fp, $signatures);
fclose($fp);