<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillableTemplate;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//FillableTemplate::init($provider);

$fillableTemplate = new FillableTemplate($provider);
$fillableTemplate->document_id = 53690143;
$fillableTemplate->fillable_fields['Text_1'] = 'Hop hey lalaley';
$fillableTemplate->fillable_fields['Number_1'] = '2016';
$fillableTemplate->fillable_fields['Checkbox_1'] = '1';
$fillableTemplate->fillable_fields['Date_1'] = '03/07/2016';

$e = $fillableTemplate->save();
dd($e);