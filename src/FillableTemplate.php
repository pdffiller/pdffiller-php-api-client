<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\DTO\FillableField;

/**
 * Class FillableTemplate
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property int $document_id
 * @property array $fillable_fields
 */
class FillableTemplate extends Model
{

    protected static $entityUri = 'fillable_template';
    const DOWNLOAD = 'download';
    const FILLED_DOCUMENTS = 'filled_document';
    const VALUES = 'values';

    public function attributes()
    {
        return [
            'document_id',
            'fillable_fields',
        ];
    }

    /**
     * @param $id
     * @return FillableTemplate
     */
    public static function dictionary($provider, $id)
    {
        $fields = static::query($provider, $id);
        $fillableFields = [];

        foreach ($fields as $fieldProperties) {
            $fillableFields[] = new FillableField($fieldProperties);
        }

        $params = ['document_id' => $id, 'fillable_fields' => $fillableFields];
        return new static($provider, $params);
    }

    /**
     * Returns model instance.
     * @param PDFfiller $provider
     * @param $id
     * @return static
     */
    public static function one($provider, $id)
    {
        return self::dictionary($provider, $id);
    }

    /**
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function download($provider, $id)
    {
        return static::query($provider, [$id, self::DOWNLOAD]);
    }

    /**
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function filledDocuments($provider, $id)
    {
        return static::query($provider, [$id, self::FILLED_DOCUMENTS]);
    }

    /**
     * Return values of fillable template`s fields
     *
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function getValues($provider, $id)
    {
        return static::query($provider, [$id, self::VALUES]);
    }

    /**
     * Return values of fillable template`s fields
     *
     * @return mixed
     */
    public function getFieldsValues()
    {
        return self::getValues($this->client, $this->document_id);
    }

    /**
     * Return list of fillable fields
     *
     * @return array
     */
    public function getFillableFields()
    {
        if (!isset($this->fillable_fields) || empty($this->fillable_fields)) {
            $this->fillable_fields = [];
        }

        return $this->fillable_fields;
    }

    /**
     * Returns array representation of an object
     *
     * @param array $options
     * @return array
     */
    public function toArray($options = [])
    {
        $options = array_merge_recursive($options, [
            'except' => ['document_id']
        ]);

        return parent::toArray($options);
    }
}
