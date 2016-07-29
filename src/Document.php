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
 * @property array $folder
 */
class Document extends Model implements Uploadable
{
    public static $entityUri = 'document';
    const DOWNLOAD = 'download';

    public function attributes()
    {
        return [
            'name',
            'type',
            'created',
            'folder',
        ];
    }

    public static function download($provider, $documentId)
    {
        return static::query($provider, [$documentId, self::DOWNLOAD]);
    }

    public function getContent()
    {
        return self::download($this->client, $this->id);
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
