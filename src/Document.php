<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Contracts\Uploadable;

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
    const DOWNLOAD_SIGNATURES = 'download_signatures';

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'name',
            'type',
            'created',
            'folder',
        ];
    }

    /**
     * Return document content
     *
     * @param $provider
     * @param $documentId
     * @return string
     */
    public static function download($provider, $documentId)
    {
        return static::query($provider, [$documentId, self::DOWNLOAD]);
    }

    /**
     * Return zip-archive of document signatures
     *
     * @param $provider
     * @param $documentId
     * @return string
     */
    public static function downloadSignatures($provider, $documentId)
    {
        return static::query($provider, [$documentId, self::DOWNLOAD_SIGNATURES]);
    }

    /**
     * Return zip-archive of document signatures
     *
     * @return string
     */
    public function getDocumentSignatures()
    {
        return self::downloadSignatures($this->client, $this->id);
    }

    /**
     * Return document content
     *
     * @return string
     */
    public function getContent()
    {
        return self::download($this->client, $this->id);
    }

    /**
     * Create link to edit a specific document
     *
     * @param int $expire |5
     * @return array
     */
    public function createConstructor($expire = 5)
    {
        $url = self::resolveFullUrl([$this->id, 'constructor'], ['expire' => $expire]);

        return static::post($this->client, $url);
    }

    /**
     * Retrieve a list of url's and hash's for a specific document
     *
     * @return array
     */
    public function getConstructorList()
    {
        return static::query($this->client, [$this->id, 'constructor']);
    }

    /**
     *  Removing one (if hash is specified) or all shared link('s) to document
     *
     * @param $hash |null
     * @return array
     */
    public function deleteConstructor($hash = null)
    {
        $url = self::resolveFullUrl([$this->id, 'constructor']);

        if (isset($hash)) {
            $url = self::resolveFullUrl([$this->id, 'constructor', $hash]);
        }

        return static::delete($this->client, $url);
    }
}
