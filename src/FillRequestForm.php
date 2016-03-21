<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

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
    /**
     * @var int
     */
    private $fillRequestId;

    protected static $baseUri = 'fill_request';
    const DOWNLOAD = 'download';
    const EXPORT = 'export';


    public function __construct($provider, $fillRequestId, $array = [])
    {
        $this->fillRequestId = $fillRequestId;
        static::setEntityUri(static::$baseUri . '/' . $fillRequestId . '/' . FillRequest::FORMS_URI);
        parent::__construct($provider, $array);
    }

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

    public function export()
    {
        return static::query($this->client, $this->id, self::EXPORT);
    }

    public function download()
    {
        return static::query($this->client, $this->id, self::DOWNLOAD);
    }
}
