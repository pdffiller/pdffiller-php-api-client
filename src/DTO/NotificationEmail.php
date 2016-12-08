<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;

/**
 * Class NotificationEmails
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $name
 * @property string $email
 */
class NotificationEmail extends AbstractObject
{
    protected $attributes = [
        'name',
        'email'
    ];
}
