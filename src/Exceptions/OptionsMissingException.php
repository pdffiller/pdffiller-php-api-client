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
    /** @var array */
    protected $options = [];

    /**
     * OptionsMissingException constructor.
     * @param array $options
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($options, $code = 0, Exception $previous = null)
    {
        $this->options = $options;
        parent::__construct("", $code, $previous);
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultMessage()
    {
        return parent::getDefaultMessage() . ': ' . implode(', ', $this->options);
    }

    /**
     * Returns options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
