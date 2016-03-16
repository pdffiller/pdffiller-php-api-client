<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class InvalidBodyException extends Exception
{
    protected $defaultMessage = 'Passing in the "body" request '
    . 'option as an array to send a POST request has been deprecated. '
    . 'Please use the "form_params" request option to send a '
    . 'application/x-www-form-urlencoded request, or a the "multipart" '
    . 'request option to send a multipart/form-data request.';
}