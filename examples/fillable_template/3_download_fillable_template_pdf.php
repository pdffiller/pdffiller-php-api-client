<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';

$fillableTemplateEntity = new \PDFfiller\OAuth2\Client\Provider\FillableTemplate($provider);

$e = $fillableTemplateEntity->download('56436761');

dd($e);