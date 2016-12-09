<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;

/**
 * Class CallbackEvent
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class CallbackEvent extends Enum
{
    const FILL_REQUEST_DONE = 'fill_request.done';
    const SIGNATURE_REQUEST_DONE = 'signature_request.done';
}
