<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;

/**
 * Class FillRequestAccess
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class DocumentAccess extends Enum
{
    const ACCESS_FULL = 'full';
    const ACCESS_SIGNATURE = 'signature';
}
