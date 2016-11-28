<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Object;

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
class FillableField extends Object
{
    protected $attributes = [
        'name',
        'type',
        'format',
        'initial',
        'required',
    ];
}