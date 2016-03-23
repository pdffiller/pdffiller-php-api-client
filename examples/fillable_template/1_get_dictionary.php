<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = FillableTemplate::dictionary($provider, 53690143);
dd($e->toArray());

