<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;

/**
 * Class SignatureRequestStatus
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class SignatureRequestStatus extends Enum
{
    const IN_PROGRESS = 'IN_PROGRESS';
    const NOT_SENT = 'NOT_SENT';
    const REJECTED = 'REJECTED';
    const SENT = 'SENT';
    const SIGNED = 'SIGNED';
    const COMPLETED = 'COMPLETED';
    const DECLINE = 'DECLINE';
    const UNSUPPORTED_STATUS_VALUE = 'UNSUPPORTED_STATUS_VALUE';
}
