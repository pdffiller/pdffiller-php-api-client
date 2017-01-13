<?php
use PDFfiller\OAuth2\Client\Provider\SignatureRequest;
use PDFfiller\OAuth2\Client\Provider\SignatureRequestRecipient;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestMethod;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestSecurityPin;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = new SignatureRequest($provider);
$e->document_id = 85998138;
//$e->method = 'sendtoeach';
///////////
$e->method = new SignatureRequestMethod(SignatureRequestMethod::SEND_TO_GROUP);
$e->envelope_name = 'group envelope';
$e->sign_in_order = false;
//////////
$e->security_pin = new SignatureRequestSecurityPin(SignatureRequestSecurityPin::STANDARD);
$e->recipients[] = new SignatureRequestRecipient($provider, [
    'email' => 'test@test.com',
    'name' => 'Test user',
    'access' => 'full',
    'require_photo' => false,
    'message_subject' => 'subject',
    'message_text' => 'message',
    'additional_documents' => [
        'doc1'
    ]
]);

dd($e->save());
