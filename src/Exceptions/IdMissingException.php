<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class IdMissingException extends Exception
{
    protected $defaultMessage = "Object must have an id property.";
}