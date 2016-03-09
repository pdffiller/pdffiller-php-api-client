<?php
use PDFfiller\OAuth2\Client\Provider\Alt\Callback;
$provider = require_once __DIR__.'/../../examples/bootstrap/initWithFabric.php';
//$callbackEntity = new \PDFfiller\OAuth2\Client\Provider\Callback($provider);
//
//$e = $callbackEntity->create(20113290, [
//    "event_id" => "fill_request.done",
//    "callback_url" => "http://pdffiller.com/callback_destination"
//]);
$callback = new Callback($provider);
$callback->document_id = 53690143;
$callback->event_id = "fill_request.done";
$callback->callback_url = "http://pdffiller.com/callback_destination";
dd($callback->save());
