<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';

$fillableTemplateEntity = new \aslikeyou\OAuth2\Client\Provider\FillableTemplate($provider);

$e = $fillableTemplateEntity->download('20267666');

dd($e);