<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;

/**
 * Class GrantType
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class GrantType extends Enum
{
    const __default = self::PASSWORD_GRANT;

    const PASSWORD_GRANT = 'password';
    const REFRESH_TOKEN_GRANT = 'refresh_token';
    const AUTHORIZATION_CODE_GRANT = 'authorization_code';
}
