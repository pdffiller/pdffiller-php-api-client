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
class Template extends Model implements Uploadable
{
    public static $entityUri = 'templates';
    const DOWNLOAD = 'download';
    const DOWNLOAD_SIGNATURES = 'download_signatures';
    const FILLED_DOCUMENTS = 'filled_documents';
    const ORIGINAL_DOCUMENT = 'original_document';
    const META = 'meta';
    const WATERMARK = 'watermark';
    const VALUES = 'values';
    const FIELDS = 'fields';
    const TYPE_CHECKBOX = 'checkmark';

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
     * Return template content
     *
     * @param $provider
     * @param $templateId
     * @return string
     */
    public static function download($provider, $templateId)
    {
        return static::query($provider, [$templateId, self::DOWNLOAD]);
    }

    /**
     * Return zip-archive of template signatures
     *
     * @param $provider
     * @param $templateId
     * @return string
     */
    public static function downloadSignatures($provider, $templateId)
    {
        return static::query($provider, [$templateId, self::DOWNLOAD_SIGNATURES]);
    }

    /**
     * Return zip-archive of template signatures
     *
     * @return string
     */
    public function getDocumentSignatures()
    {
        return self::downloadSignatures($this->client, $this->id);
    }

    /**
     * Return template content
     *
     * @return string
     */
    public function getContent()
    {
        return self::download($this->client, $this->id);
    }

    /**
     * Create link to edit a specific template
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
     * Retrieve a list of url's and hash's for a specific template
     *
     * @return array
     */
    public function getConstructorList()
    {
        return static::query($this->client, [$this->id, 'constructor']);
    }

    /**
     *  Removing one (if hash is specified) or all shared link('s) to template
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

    /**
     * Fill template with named fields
     *
     * @param array $fields
     * @return array
     */
    public function fill($fields = [])
    {
        $url = self::resolveFullUrl([$this->id]);

        if(isset($this->properties['fillable_fields'])) {
            $fields = $this->properties['fillable_fields'];
        }

        foreach ($fields as $index => $field) {
            if (!is_array($field)) {
                continue;
            }

            unset($fields[$index]);

            if (!isset($field['name']) || !isset($field['value'])) {
                continue;
            }

            $name = $field['name'];
            $value = $field['value'];
            $type = isset($field['type']) ? $field['type'] : '';

            if ($type === self::TYPE_CHECKBOX) {
                $value = intval(boolval($value));
            }

            $fields[$name] = $value;
        }

        return static::post($this->client, $url, [
            'json' => [
                'fillable_fields' => $fields
            ]
        ]);
    }

    /**
     * Get all fields from template
     *
     * @return array
     */
    public function fields()
    {
        return static::query($this->client, [$this->id, self::FIELDS]);
    }

    /**
     * Get all filled documents from template
     *
     * @return array
     */
    public function getFilledDocument()
    {
        return static::query($this->client, [$this->id, self::FILLED_DOCUMENTS]);
    }

    /**
     * Return original document
     *
     * @return string
     */
    public function getOriginalDocument()
    {
        return self::downloadOriginalDocument($this->client, $this->id);
    }

    /**
     * Download original document
     *
     * @param $provider
     * @param $templateId
     * @return mixed
     */
    public function downloadOriginalDocument($provider, $templateId)
    {
        return static::query($provider, [$templateId, self::ORIGINAL_DOCUMENT]);
    }

    /**
     * Get template meta
     *
     * @return array
     */
    public function meta()
    {
        return static::query($this->client, [$this->id, self::META]);
    }

    /**
     * Get template meta
     *
     * @return array
     */
    public function watermark($watermarkText)
    {
        $url = self::resolveFullUrl([$this->id, self::WATERMARK], ['text' => $watermarkText]);

        return static::post($this->client, $url);
    }
}
