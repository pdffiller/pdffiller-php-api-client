<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$e = FillableForm::one($provider, 345345)
    ->form(45343534)
    ->download();
dd($e);
