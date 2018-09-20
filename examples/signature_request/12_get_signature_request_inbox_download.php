<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
use \PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestStatus;
use PDFfiller\OAuth2\Client\Provider\Core\Job;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$signatureRequestEntity = new \PDFfiller\OAuth2\Client\Provider\SignatureRequest($provider);


$signatureRequests = new SignatureRequest($provider);
$signatureRequestsAsync = new Job($signatureRequests);

$signatureRequestsAsync->getInboxContent([
    'status' => new SignatureRequestStatus(SignatureRequestStatus::SENT) /* supports only x, in_progress, sent */,
    'perpage' => 3,
    'datefrom' => '2016-05-01',
    'dateto' => '2016-12-30'
]);

while (!$signatureRequestsAsync->isReady()) {
    sleep(2);
}

$fp = fopen('inbox.zip', 'w');
fwrite($fp, $signatureRequestsAsync->getResult());
fclose($fp);
die();
