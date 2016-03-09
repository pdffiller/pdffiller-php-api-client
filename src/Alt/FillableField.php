<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 09.03.16
 * Time: 12:37
 */

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Object;

/**
 * Class FillableField
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property $name
 * @property $type
 * @property $format
 * @property $initial
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