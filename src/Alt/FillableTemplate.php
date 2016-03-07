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

    protected $attributes = [
        'document_id',
        'fillable_fields',
    ];
    /**
     * @param $id
     * @return mixed
     */
    public static function dictionary($id)
    {
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

    /**
     * @param $id
     * @param array $fields ex: [ 'Text_1' => 'hello world', 'Number_1' => '123' ]
     * @return mixed
     */
    public function makeFillableTemplate()
    {
        $params = $this->toArray();
        return static::post(static::getUri(), $params);
    }
}
