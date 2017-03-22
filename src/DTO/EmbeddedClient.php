<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;
use PDFfiller\OAuth2\Client\Provider\Core\ListObject;

/**
 * Class FieldsAccess
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $domain
 * @property bool $allow_all_domains
 */
class EmbeddedClient extends AbstractObject
{
    /** @var array */
    protected $casts = [
        'allow_all_domains' => 'bool',
    ];

    /** @var array */
    protected $attributes = [
        'domain',
        'allow_all_domains'
    ];
}
