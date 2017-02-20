<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;
use PDFfiller\OAuth2\Client\Provider\Enums\FilledFormExportFormat;
use PDFfiller\OAuth2\Client\Provider\FillRequestForm;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$exportedForm = FillRequest::one($provider, 86408748)->form(260560)->export(FilledFormExportFormat::CSV);
//$fillRequestForm = new FillRequestForm($provider, 86408748, ['id' => 260560]);
//$exportedForm = $fillRequestForm->export(FilledFormExportFormat::HTML);
dd($exportedForm);
