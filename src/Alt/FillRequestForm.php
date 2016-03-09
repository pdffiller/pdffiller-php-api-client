<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

class FillRequestForm extends Model
{
    /**
     * @var int
     */
    private $fillRequestId;

    protected static $baseUri = 'fill_request';


    public function __construct($provider, $fillRequestId, $array = [])
    {
        $this->fillRequestId = $fillRequestId;
        static::setEntityUri(static::$baseUri . '/' . $fillRequestId . '/filled_form');
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
        return static::query($this->client, $this->id, 'export');
    }

    public function download()
    {
        return static::query($this->client, $this->id, 'download');
    }
}
