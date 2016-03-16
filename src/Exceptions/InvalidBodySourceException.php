<?php

namespace PDFfiller\OAuth2\Client\Provider\Exceptions;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;

class InvalidBodySourceException extends Exception
{
    protected $defaultMessage = 'You cannot use '
    . 'form_params and multipart at the same time. Use the '
    . 'form_params option if you want to send application/'
    . 'x-www-form-urlencoded requests, and the multipart '
    . 'option to send multipart/form-data requests.';
}