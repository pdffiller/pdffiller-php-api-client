<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

/**
 * Class ResponseException
 * Handle response errors.
 * @package PDFfiller\OAuth2\Client\Provider\Exceptions
 */
class ResponseException extends ErrorsException
{
    public function getDefaultMessage()
    {
        $string = "";
        foreach ($this->errors as $error) {
            $message = isset($error['message']) ? $error['message'] : $error;
            $string .= trim($message, '.') . '. ';
        }
        return parent::getDefaultMessage() . '.' . PHP_EOL . $string;
    }
}
