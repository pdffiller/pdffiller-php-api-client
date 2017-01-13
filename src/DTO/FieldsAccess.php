<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;
use PDFfiller\OAuth2\Client\Provider\Core\ListObject;

/**
 * Class FieldsAccess
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property ListObject $allow
 * @property ListObject $deny
 */
class FieldsAccess extends AbstractObject
{
    /** @var array */
    protected $casts = [
        'allow' => 'list',
        'deny' => 'list',
    ];

    /** @var array */
    protected $attributes = [
        'allow',
        'deny'
    ];

    /**
     * FieldsAccess constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        $properties = array_merge([
            'allow' => new ListObject(),
            'deny' => new ListObject()
        ], $properties);

        parent::__construct($properties);
    }
}
