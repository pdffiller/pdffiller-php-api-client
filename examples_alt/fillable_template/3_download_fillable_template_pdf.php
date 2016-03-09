<?php
use PDFfiller\OAuth2\Client\Provider\Alt\FillableTemplate;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';

//$fillableTemplateEntity = new \PDFfiller\OAuth2\Client\Provider\FillableTemplate($provider);

$e = FillableTemplate::download($provider, '20267666');

dd($e);