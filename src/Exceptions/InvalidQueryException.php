<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class InvalidQueryException extends Exception
{
    protected $defaultMessage = "Query must be a string or an array";
}