<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class ValidationException extends Exception
{
    protected $defaultMessage = "Validation failed.";
}