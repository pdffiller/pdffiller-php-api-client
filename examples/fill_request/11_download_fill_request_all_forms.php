<?php
use PDFfiller\OAuth2\Client\Provider\FillRequest;


$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$fillRequest = new FillRequest($provider, ['id' => 90818604]);

do {
    $response = $fillRequest->download();
    sleep(2);
} while(!empty($response['job_id']) || !empty($response['status']));

dd($response);
