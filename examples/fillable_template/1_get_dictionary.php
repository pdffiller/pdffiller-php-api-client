<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = FillableTemplate::dictionary($provider, 94009907);

dd($template);

