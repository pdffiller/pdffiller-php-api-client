<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;

/**
 * Class TypeException
 * @package PDFfiller\OAuth2\Client\Provider\Exceptions
 */
class TypeException extends Exception
{
    /** @var array */
    protected $typesAllowed = [];

    /**
     * TypeException constructor.
     * @param array $types
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($types = [], $message = "", $code = 0, Exception $previous = null)
    {
        $this->typesAllowed = $types;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns allowed types
     *
     * @return array
     */
    public function getTypesAllowed()
    {
        return $this->typesAllowed;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultMessage()
    {
        $default = parent::getDefaultMessage();
        if (!empty($this->typesAllowed)) {
            $default .= ". Allowed types: " . implode(', ', $this->typesAllowed);
        }
        return $default;
    }
}
