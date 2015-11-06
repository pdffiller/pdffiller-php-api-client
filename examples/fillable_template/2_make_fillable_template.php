<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';

$fillableTemplateEntity = new \aslikeyou\OAuth2\Client\Provider\FillableTemplate($provider);

$e = $fillableTemplateEntity->makeFillableTemplate('20267665', [
    "Text_1"=> "Quam voluptatem quas.",
    "Number_1"=> 24352,
    "Checkbox_1"=> "0",
    "Date_1"=> "02/20/2016"
]);
dd($e);