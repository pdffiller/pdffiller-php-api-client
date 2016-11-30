<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = FillableTemplate::filledDocuments($provider, 20708874);
dd($e);

