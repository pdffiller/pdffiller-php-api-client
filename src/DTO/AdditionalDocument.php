<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;

/**
 * Class AdditionalDocument
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $name
 */
class AdditionalDocument extends AbstractObject
{
    protected $attributes = [
        'name',
    ];
}
