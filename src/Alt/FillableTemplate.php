<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;

/**
 * Class FillableTemplate
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property $document_id
 * @property $fillable_fields array
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

    public function rules()
    {
        return [
            'document_id' => 'integer',
            'fillable_fields' => 'array',
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
        return static::query($provider, $id, self::DOWNLOAD);
    }
}
