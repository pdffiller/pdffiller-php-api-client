<?php
use PDFfiller\OAuth2\Client\Provider\FillableTemplate;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$fields['fillable_fields'] = [
    'Text_1' => 'Fillable field text',
    'Number_1' => '2017',
    'Checkbox_1' => '1',
    'Date_1' => '01/13/2017',
];
$fillableTemplate = new FillableTemplate($provider, $fields);
$fillableTemplate->document_id = 67158068;
//different ways to change the list of fillable fields
//$fillableTemplate->fillable_fields = [
//    'Text_1' => 'Updated text',
//    'Number_1' => '123',
//    'Checkbox_1' => '0',
//    'Date_1' => '02/13/2017',
//];

//$fillableTemplate->fillable_fields['Text_1'] = 'Fillable field text';
//$fillableTemplate->fillable_fields['Number_1'] = '2017';
//$fillableTemplate->fillable_fields['Checkbox_1'] = '1';
//$fillableTemplate->fillable_fields['Date_1'] = '01/13/2017';

$result = $fillableTemplate->save();
dd($result);