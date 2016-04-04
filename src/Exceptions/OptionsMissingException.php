<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;

/**
 * Class OptionsMissingException
 * Handle missing required options exception.
 * @package PDFfiller\OAuth2\Client\Provider\Exceptions
 */
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
        return parent::getDefaultMessage() . ': ' . implode(', ', $this->options);
    }

    public function getOptions()
    {
        return $this->options;
    }
}
