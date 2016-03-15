<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;


use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Token
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property array $data
 * @property string $hash
 */
class Token extends Model
{

    protected static $entityUri = 'token';
    const RULES_KEY = 'token';

    public function attributes()
    {
        return [
            'id',
            'hash',
            'data',
        ];
    }
}
