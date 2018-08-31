<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = new Template($provider,['id'=> 216865300]);
$originalDocument = $template->getOriginalDocument();
$fp = fopen('original_document.pdf', 'w');
fwrite($fp, $originalDocument);
fclose($fp);
