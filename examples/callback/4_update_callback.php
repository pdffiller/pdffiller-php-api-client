<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$callback = Callback::one($provider, 684);
$callback->document_id = "53690157";
$callback->event_id = "fill_request.done";
$callback->callback_url = "http://pdffiller.com/callback_destination";
dd($callback->save());
