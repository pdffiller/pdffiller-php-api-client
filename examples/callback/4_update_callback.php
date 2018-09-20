<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$callback = Callback::one($provider, 324234);
$callback->document_id = 45345345;
$callback->event_id = "constructor.done";
$callback->callback_url = "http://pdffiller.com/callback_destination";
dd($callback->save());
