<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;

/**
 * Class AdditionalDocument
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $document_request_notification
 */
class AdditionalDocument extends AbstractObject
{
    /** @var array */
    protected $attributes = [
        'document_request_notification',
    ];

    /**
     * AdditionalDocument constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        if (is_string($properties)) {
            $properties = [
                'document_request_notification' => $properties
            ];
        }

        parent::__construct($properties);
    }
}
