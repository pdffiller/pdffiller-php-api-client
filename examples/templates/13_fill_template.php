<?php
use PDFfiller\OAuth2\Client\Provider\Template;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillableFields = [
    'Text_1' => 'Fillable field text'
];
$template = new Template($provider,['id'=> 216865300, 'fillable_fields' => $fillableFields]);
$e = $template->fill();
dd($e);
