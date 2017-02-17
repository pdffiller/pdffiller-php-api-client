<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Enums\FilledFormExportFormat;

/**
 * Class FillRequestForm
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property integer $document_id
 * @property string $name
 * @property string $email
 * @property string $date
 * @property integer $id
 */
class FillRequestForm extends Model
{
    const DOWNLOAD = 'download';
    const EXPORT = 'export';

    /** @var int */
    private $fillRequestId;

    /** @var string */
    protected static $baseUri = 'fill_request';

    /**
     * FillRequestForm constructor.
     * @param PDFfiller $provider
     * @param int $fillRequestId
     * @param array $array
     */
    public function __construct($provider, $fillRequestId, $array = [])
    {
        $this->fillRequestId = $fillRequestId;
        static::setEntityUri(static::$baseUri . '/' . $fillRequestId . '/' . FillRequest::FORMS_URI);
        parent::__construct($provider, $array);
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'document_id',
            'name',
            'email',
            'date',
            'ip',
        ];
    }

    /**
     * @return int
     */
    public function getFillRequestId()
    {
        return $this->fillRequestId;
    }

    /**
     * @param int $fillRequestId
     */
    public function setFillRequestId($fillRequestId)
    {
        $this->fillRequestId = $fillRequestId;
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

        return static::query($this->client, [$this->id, self::EXPORT], ['format' => $format->getValue()]);
    }

    /**
     * Downloads form
     *
     * @return mixed
     */
    public function download()
    {
        return static::query($this->client, [$this->id, self::DOWNLOAD]);
    }
}
