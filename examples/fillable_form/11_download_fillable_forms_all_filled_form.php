<?php
use PDFfiller\OAuth2\Client\Provider\FillableForm;
use PDFfiller\OAuth2\Client\Provider\Core\Job;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillableForm = new FillableForm($provider, ['fillable_form_id' => 43545345]);

$fillableFormAsync = new Job($fillableForm);
$fillableFormAsync->download();

while (!$fillableFormAsync->isReady()) {
    sleep(2);
}

dd($fillableFormAsync);
