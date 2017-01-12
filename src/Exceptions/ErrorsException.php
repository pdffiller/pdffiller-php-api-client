<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;

/**
 * Class ErrorsException
 * Handle exceptions with errors messages.
 * @package PDFfiller\OAuth2\Client\Provider\Exceptions
 */
class ErrorsException extends Exception
{
    /** @var array */
    protected $errors = [];

    /**
     * ErrorsException constructor.
     * @param string $errors
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($errors, $message = "", $code = 0, Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
