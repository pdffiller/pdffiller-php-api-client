<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\DTO\FillableField;

/**
 * Class FillableTemplate
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property int $document_id
 * @property ListObject $fillable_fields
 */
class FillableTemplate extends Model
{
    const DOWNLOAD = 'download';
    const FILLED_DOCUMENTS = 'filled_document';
    const VALUES = 'values';

    /** @var string */
    protected static $entityUri = 'fillable_template';

    protected $casts = [
        'fillable_fields' => ['list_of', FillableField::class],
    ];


        /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'document_id',
            'fillable_fields',
            'created',
        ];
    }

    /**
     * @param $id
     * @return FillableTemplate
     */
    public static function dictionary($provider, $id)
    {
        $fields = static::query($provider, $id);
        $params = ['document_id' => $id, 'fillable_fields' => $fields];

        return new static($provider, $params);
    }

    /**
     * @inheritdoc
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
        $documents = static::query($provider, [$id, self::FILLED_DOCUMENTS]);
        $documents['items'] = static::formItems($provider, $documents);

        return new ModelsList($documents);
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
     * Fillable fields mass assignment
     * @param array $fillableFields
     */
    public function setFillableFieldsField($fillableFields = [])
    {
        $fields = [];

        foreach ($fillableFields as $name => $value) {
            $fields[] = new FillableField([
                'name' => $name,
                'value' => $value,
            ]);
        }

        $this->properties['fillable_fields'] = new ListObject($fields);
    }

    /**
     * @inheritdoc
     */
    public function save($options = [])
    {
        $this->exists = false;
        $fields = $this->fillable_fields->toArray();

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
            $fields[$name] = $value;
        }

        $this->properties['fillable_fields'] = $fields;

        return parent::save($options);
    }
}
