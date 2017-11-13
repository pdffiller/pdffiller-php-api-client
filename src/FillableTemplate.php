<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\DTO\FillableField;
use PDFfiller\OAuth2\Client\Provider\DTO\FillableFieldsList;

/**
 * Class FillableTemplate
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property int $document_id
 * @property FillableFieldsList $fillable_fields
 */
class FillableTemplate extends Model
{
    const DOWNLOAD = 'download';
    const FILLED_DOCUMENTS = 'filled_document';
    const VALUES = 'values';
    const TYPE_CHECKBOX = 'checkmark';

    /** @var string */
    protected static $entityUri = 'fillable_template';

    protected $casts = [
        'fillable_fields' => FillableFieldsList::class,
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
     * @param $provider
     * @param $id
     * @return static
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
    public static function one(PDFfiller $provider, $id)
    {
        return self::dictionary($provider, $id);
    }

    /**
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function download(PDFfiller $provider, $id)
    {
        return static::query($provider, [$id, self::DOWNLOAD]);
    }

    /**
     * @param PDFfiller $provider
     * @param $id
     * @return mixed
     */
    public static function filledDocuments(PDFfiller $provider, $id)
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
    public static function getValues(PDFfiller $provider, $id)
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
     * Fillable fields mass assignment
     * @param array $fillableFields
     */
    public function setFillableFieldsField($fillableFields = [])
    {
        if ($fillableFields instanceof FillableFieldsList) {
            $this->properties['fillable_fields'] = $fillableFields;

            return;
        }

        $fields = [];

        foreach ($fillableFields as $name => $value) {
            $fields[] = new FillableField([
                'name' => $name,
                'value' => $value,
            ]);
        }

        $this->properties['fillable_fields'] = new FillableFieldsList($fields);
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
            $type = isset($field['type']) ? $field['type'] : '';

            if ($type === self::TYPE_CHECKBOX) {
                $value = intval(boolval($value));
            }

            $fields[$name] = $value;
        }

        $this->properties['fillable_fields'] = $fields;

        return parent::save($options);
    }
}
