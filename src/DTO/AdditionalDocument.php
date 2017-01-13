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
    /** @var array */
    protected $attributes = [
        'name',
    ];

    /**
     * AdditionalDocument constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        if (is_string($properties)) {
            $properties = [
                'name' => $properties
            ];
        }

        parent::__construct($properties);
    }
}
