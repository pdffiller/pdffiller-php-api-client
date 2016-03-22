<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class IdMissingException extends Exception
{
    const EXCEPTION_KEY = 'idMissing';
}