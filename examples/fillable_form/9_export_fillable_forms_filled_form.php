<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;
use PDFfiller\OAuth2\Client\Provider\Enums\FilledFormExportFormat;
use PDFfiller\OAuth2\Client\Provider\FilledForm;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$exportedForm = FillableForm::one($provider, 8494949)
    ->form(3434)
    ->export(FilledFormExportFormat::CSV);

dd($exportedForm);
