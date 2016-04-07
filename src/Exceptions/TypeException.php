<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class TypeException extends Exception
{
    protected $typesAllowed = [];

    public function __construct($types = [], $message = "", $code = 0, Exception $previous = null)
    {
        $this->typesAllowed = $types;
        parent::__construct($message, $code, $previous);
    }

    public function getTypesAllowed()
    {
        return $this->typesAllowed;
    }

    protected function getDefaultMessage()
    {
        $default = parent::getDefaultMessage();
        if (!empty($this->typesAllowed)) {
            $default .= ". Allowed types: " . implode(', ', $this->typesAllowed);
        }
        return $default;
    }
}