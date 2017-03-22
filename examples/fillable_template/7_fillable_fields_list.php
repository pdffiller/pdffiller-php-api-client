<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;
use \PDFfiller\OAuth2\Client\Provider\DTO\FillableField;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$template = FillableTemplate::dictionary($provider, 94009907);

$template->fillable_fields->eachFillable(function (FillableField $field) {
    $field->value = "test";
});

dd($template->fillable_fields->toArray(), $template->fillable_fields->getOnlyFillable()->toArray());

