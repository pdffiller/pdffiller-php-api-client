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
        $params = ['document_id' => $id, 'fillable_fields' => []];
        foreach ($fields as $fieldProperties) {
            $params['fillable_fields'][] = new FillableField($fieldProperties);
        }

        return new static($provider, $params);
    }

    /**
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function download($provider, $id)
    {
        return static::query($provider, $id, 'download');
    }
}
