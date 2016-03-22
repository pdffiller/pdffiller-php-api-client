<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class InvalidQueryException extends Exception
{
    const EXCEPTION_KEY = 'invalidQuery';
}