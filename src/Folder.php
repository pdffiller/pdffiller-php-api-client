<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Folder
 * @package PDFfiller\OAuth2\Client\Provider
 * @property string $name
 * @property string $type
 * @property string $created
 */
class Folder extends Model
{
    public static $entityUri = 'folder';

    public function attributes()
    {
        return [
            'id',
            'name',
            'document_count',
        ];
    }
}
