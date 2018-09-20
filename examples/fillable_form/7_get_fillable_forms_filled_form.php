<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = FillableForm::one($provider, 34545)->form(45543);
dd($e);
