<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

class Exception extends \Exception
{
    protected $defaultMessage = "";
    const EXCEPTION_KEY = "exception";

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if (empty($message)) {
            $message = $this->getDefaultMessage();
        }
        parent::__construct($message, $code, $previous);
    }

    protected function getDefaultMessage()
    {
        return ExceptionsMessages::getMessage(static::EXCEPTION_KEY);
    }
}