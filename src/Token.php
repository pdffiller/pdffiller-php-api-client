<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Token
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property ListObject $data
 * @property string $hash
 */
class Token extends Model
{
    protected $casts = [
        'data' => 'list'
    ];
}
