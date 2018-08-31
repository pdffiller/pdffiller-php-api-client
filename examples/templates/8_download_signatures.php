<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$signatures = Template::downloadSignatures($provider, 216865300);
$fp = fopen('signatures.zip', 'w');
fwrite($fp, $signatures);
fclose($fp);
