<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;
use PDFfiller\OAuth2\Client\Provider\Core\ListObject;

/**
 * Class FillableField
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $name
 * @property string $type
 * @property string $format
 * @property mixed $initial
 * @property boolean $required
 * @property string $maxChars
 * @property string $maxLines
 * @property ListObject $list
 */
class FillableField extends AbstractObject
{
    /** @var array */
    protected $attributes = [
        'name',
        'type',
        'format',
        'initial',
        'required',
        'maxChars',
        'maxLines',
        'list',
    ];

    protected $casts = [
        'list' => 'list'
    ];
}
