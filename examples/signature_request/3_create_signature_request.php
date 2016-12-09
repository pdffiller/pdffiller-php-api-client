<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = new SignatureRequest($provider);
$e->document_id = 20577499;
//$e->method = 'sendtoeach';
///////////
$e->method = "sendtogroup";
$e->envelope_name = 'group envelope';
$e->sign_in_order = false;
//////////
$e->security_pin = 'standard';
$e->recipients[] = [
    'email' => 'test@test.com',
    'name' => 'Test user',
    'access' => 'full',
    'require_photo' => true,
    'message_subject' => 'subject',
    'message_text' => 'message',
];

dd($e->save());
