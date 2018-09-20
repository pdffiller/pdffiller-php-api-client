<?php
use PDFfiller\OAuth2\Client\Provider\Callback;
$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';
$callback = new Callback($provider);
$callback->document_id = 172323999;
$callback->event_id = "constructor.done";
$callback->callback_url = "http://pdffiller.com";
dd($callback->save());
