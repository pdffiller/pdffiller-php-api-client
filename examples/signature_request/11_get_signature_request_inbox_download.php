<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);

do {
    $inbox = SignatureRequest::inboxDownload($provider, ['status' => 'signed' /* signed, in_progress, sent */, 'perpage' => 3, 'datefrom' => '2016-05-01', 'dateto' => '2016-12-30' ]);
    sleep(2);
} while(!empty($inbox['job_id']) || !empty($inbox['status']));

if (is_string($inbox)) {
    $fp = fopen('inbox.zip', 'w');
    fwrite($fp, $inbox);
    fclose($fp);
    die();
}
dd($inbox);
