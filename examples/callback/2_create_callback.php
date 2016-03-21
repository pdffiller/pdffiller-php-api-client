<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$callback = new Callback($provider);
$callback->document_id = 53690143;
$callback->event_id = "fill_request.done";
$callback->callback_url = "http://pdffiller.com/callback_destination";
dd($callback->save());
