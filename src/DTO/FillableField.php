<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;

/**
 * Class FillableField
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $name
 * @property string $type
 * @property string $format
 * @property mixed $initial
 * @property boolean $required
 */
class FillableField extends AbstractObject
{
    protected $attributes = [
        'name',
        'type',
        'format',
        'initial',
        'required',
    ];
}