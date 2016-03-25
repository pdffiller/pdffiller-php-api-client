<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\Uploadable;

/**
 * Class Document
 * @package PDFfiller\OAuth2\Client\Provider
 * @property string $name
 * @property string $type
 * @property string $created
 */
class Document extends Model implements Uploadable
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

    public static function getUrlKey()
    {
        return 'documentUrl';
    }

    public static function getMultipartKey()
    {
        return 'documentMultipart';
    }
}