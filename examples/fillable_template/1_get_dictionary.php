<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';

$fillableTemplateEntity = new \aslikeyou\OAuth2\Client\Provider\FillableTemplate($provider);

$e = $fillableTemplateEntity->dictionary('20267665');
dd($e);

