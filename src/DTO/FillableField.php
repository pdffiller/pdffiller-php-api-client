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
 * @property bool $allowCustomText
 * @property string $value
 * @property string $label
 * @property string $radioGroup
 * @property bool $fillable
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
        'radioGroup',
        'allowCustomText',
        'value',
        'label',
        'fillable',
    ];

    protected $casts = [
        'list' => 'list',
        'allowCustomText' => 'bool',
        'fillable' => 'bool',
    ];
}
