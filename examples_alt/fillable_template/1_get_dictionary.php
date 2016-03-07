<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillableTemplate;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
FillableTemplate::init($provider);
$fillableTemplateEntity = new \PDFfiller\OAuth2\Client\Provider\FillableTemplate($provider);

//$e = $fillableTemplateEntity->dictionary('20267665');
$e = FillableTemplate::one(56436761);
dd($e);

