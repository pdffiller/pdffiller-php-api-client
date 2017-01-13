<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$fillableTemplate = new FillableTemplate($provider);
$fillableTemplate->document_id = 67158068;
$fillableTemplate->fillable_fields['Text_1'] = 'Fillable field text';
$fillableTemplate->fillable_fields['Number_1'] = '2017';
$fillableTemplate->fillable_fields['Checkbox_1'] = '1';
$fillableTemplate->fillable_fields['Date_1'] = '01/13/2017';

$e = $fillableTemplate->save();
dd($e);