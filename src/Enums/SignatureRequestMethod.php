<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;

/**
 * Class SignatureRequestMethod
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class SignatureRequestMethod extends Enum
{
    const SEND_TO_GROUP = 'sendtogroup';
    const SEND_TO_EACH = 'sendtoeach';
}
