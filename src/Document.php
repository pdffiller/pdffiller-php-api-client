<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Document
 * @package PDFfiller\OAuth2\Client\Provider
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
}
