<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class OptionsMissingException extends Exception
{
    const EXCEPTION_KEY = 'optionsMissing';
    protected $options = [];

    public function __construct($options, $code = 0, Exception $previous = null)
    {
        $this->options = $options;
        parent::__construct("", $code, $previous);
    }

    protected function getDefaultMessage()
    {
        return parent::getDefaultMessage() . ': ' . implode(', ', $this->options);
    }

    public function getOptions()
    {
        return $this->options;
    }
}