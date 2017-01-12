<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

/**
 * Class Exception
 *
 * Basic exception class
 *
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class Exception extends \Exception
{
    /** @var string */
    protected $defaultMessage = "";

    /**
     * Exception constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if (empty($message)) {
            $message = $this->getDefaultMessage();
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the default message
     *
     * @return string
     */
    protected function getDefaultMessage()
    {
        return (new ExceptionsMessages($this))->getMessage();
    }
}
