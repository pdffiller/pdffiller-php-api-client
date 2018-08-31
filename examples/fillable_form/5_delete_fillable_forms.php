<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = FillableForm::deleteOne($provider, 232323);
dd($e);
