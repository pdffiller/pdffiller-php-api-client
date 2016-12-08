<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;

use PDFfiller\OAuth2\Client\Provider\Core\Enum;

class FillRequestNotifications extends Enum
{
    const __default = self::DISABLED;

    const DISABLED = 'disabled';
    const ENABLED = 'enabled';
    const WITH_PDF = 'with_pdf';
}