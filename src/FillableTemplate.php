<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

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
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function download($provider, $id)
    {
        return static::query($provider, [$id, self::DOWNLOAD]);
    }

    public function getFillableFields()
    {
        if (!isset($this->fillable_fields) || empty($this->fillable_fields)) {
            $this->fillable_fields = [];
        }

        return $this->fillable_fields;
    }
}
