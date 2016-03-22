<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;


abstract class ExceptionsMessages
{
    public static function getMessage($exception, $locale = "en")
    {
        $messages = self::getMessages($locale);

        return $messages[$exception] ?: (ucfirst($exception) . "Exception");
    }

    protected static function getMessages($locale = "en")
    {
        $path = __DIR__ . "/../Messages/" . $locale . "/messages.json";
        if (file_exists($path)) {
            $jsonMessages = file_get_contents($path);
            return json_decode($jsonMessages, true);
        }

        return null;
    }
}
