<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillRequest = FillableForm::all($provider);
dd($fillRequest->toArray());
