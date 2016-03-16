<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 16.03.16
 * Time: 11:15
 */

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class InvalidRequestException extends Exception
{
    private static $allowedRequests = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
    ];

    protected function getDefaultMessage()
    {
        return 'Allowed request types are: ' . implode(',', self::$allowedRequests) . '.';
    }
}