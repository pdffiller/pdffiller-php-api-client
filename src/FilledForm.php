<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Contracts\AdditionalDocuments;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\Enums\FilledFormExportFormat;

/**
 * Class FillRequestForm
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property integer $document_id
 * @property string $name
 * @property string $email
 * @property string $date
 * @property integer $filled_form_id
 */
class FilledForm extends Model implements AdditionalDocuments
{
    const DOWNLOAD = 'download';
    const EXPORT = 'export';

    protected $primaryKey = 'filled_form_id';

    /** @var int */
    private $fillableFormId;

    /** @var string */
    protected static $baseUri = 'fillable_forms';

    /**
     * FilledForm constructor.
     * @param $provider
     * @param $fillableFormId
     * @param array $array
     * @throws \ReflectionException
     */
    public function __construct($provider, $fillableFormId, $array = [])
    {
        $this->fillableFormId = $fillableFormId;
        static::setEntityUri(static::$baseUri . '/' . $fillableFormId . '/' . FillableForm::FORMS_URI);
        parent::__construct($provider, $array);
    }

    /**
     * @return int
     */
    public function getFillableFormId()
    {
        return $this->fillableFormId;
    }

    /**
     * @param int $fillableFormId
     */
    public function setFillableFormId($fillableFormId)
    {
        $this->fillableFormId = $fillableFormId;
    }

    /**
     * Exports form
     *
     * @param string|FilledFormExportFormat $format
     * @return mixed
     */
    public function export($format = FilledFormExportFormat::JSON)
    {
        if (! $format instanceof FilledFormExportFormat) {
            $format = new FilledFormExportFormat($format);
        }

        return static::query($this->client, [$this->filled_form_id, self::EXPORT], ['format' => $format->getValue()]);
    }

    /**
     * Downloads form
     *
     * @return mixed
     */
    public function download()
    {
        return static::query($this->client, [$this->filled_form_id, self::DOWNLOAD]);
    }

    /**
     * @inheritdoc
     */
    public function additionalDocuments($parameters = [])
    {
        $response = static::query($this->client, [$this->filled_form_id, self::ADDITIONAL_DOCUMENTS], $parameters);
        $response['items'] = array_map(function ($document) {
            return $this->createAdditionalDocument($document);
        }, $response['items']);

        return new ModelsList($response);
    }

    /**
     * @inheritdoc
     */
    public function additionalDocument($documentId, $parameters = [])
    {
        $response = static::query($this->client, [$this->filled_form_id, self::ADDITIONAL_DOCUMENTS, $documentId], $parameters);

        return $this->createAdditionalDocument($response);
    }

    /**
     * @param array $parameters
     * @return mixed
     * @throws Exceptions\InvalidQueryException
     * @throws Exceptions\InvalidRequestException
     */
    public function downloadAdditionalDocuments(array $parameters = [])
    {
        return self::query($this->client, [
            $this->filled_form_id,
            self::ADDITIONAL_DOCUMENTS,
            self::ADDITIONAL_DOCUMENTS_DOWNLOAD,
        ], $parameters);
    }

    /**
     * @inheritdoc
     */
    public function createAdditionalDocument($parameters = [])
    {
        return new FillRequestAdditionalDocument($this->client, $this->fillableFormId, $this->filled_form_id, $parameters);
    }
}
