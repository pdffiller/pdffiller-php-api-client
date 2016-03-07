<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

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
     * @return mixed
     */
    public static function dictionary($id)
    {
        //TODO: make fillable field model
        return static::one($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function download($id)
    {
        return static::query($id, 'download');
    }
}
