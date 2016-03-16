<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class OptionsMissingException extends Exception
{
    protected $options = [];

    public function __construct($options, $code = 0, Exception $previous = null)
    {
        $this->options = $options;
        parent::__construct("", $code, $previous);
    }

    protected function getDefaultMessage()
    {
        return 'Required options not defined: ' . implode(', ', $this->options);
    }
}