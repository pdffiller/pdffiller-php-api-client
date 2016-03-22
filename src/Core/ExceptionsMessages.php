<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;


abstract class ExceptionsMessages
{
    public static function getMessage($exception, $locale = "en")
    {
        $messages = self::getMessages();
        if (isset($messages[$exception]) && !empty($messages[$exception])) {
            return $messages[$exception][$locale] ?: $messages[$exception]["en"];
        }

        return null;
    }

    protected static function getMessages()
    {
        $jsonMessages = file_get_contents(__DIR__ . "/../Exceptions/messages.json");

        return json_decode($jsonMessages, true);
    }
}