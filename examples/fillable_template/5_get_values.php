<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = FillableTemplate::getValues($provider, 20609837);
dd($e);

