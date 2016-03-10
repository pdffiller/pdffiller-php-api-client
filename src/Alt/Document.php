<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Document
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 * @property string $name
 * @property string $type
 * @property string $created
 */
class Document extends Model
{
    public static $entityUri = 'document';

    public function attributes()
    {
        return [
            'name',
            'type',
            'created',
        ];
    }

    public static function all($provider, $page = 1)
    {
        $params = static::query($provider, '?page=' . $page);
        return static::formItems($provider, $params);
    }
}