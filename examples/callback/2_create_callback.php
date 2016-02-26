<?php

$provider = require_once __DIR__.'/../bootstrap/initWithFabric.php';
$callbackEntity = new \PDFfiller\OAuth2\Client\Provider\Callback($provider);

$e = $callbackEntity->create(20113290, [
    "event_id" => "fill_request.done",
    "callback_url" => "http://pdffiller.com/callback_destination"
]);
dd($e);
