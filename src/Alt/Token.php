<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;


use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Token
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property array $data
 */
class Token extends Model
{

    protected static $entityUri = 'token';

    public function attributes()
    {
        return [
            'id',
            'hash',
            'data',
        ];
    }

    public function rules()
    {
        return [
            'hash' => 'string',
            'data' => 'array',
        ];
    }
}
