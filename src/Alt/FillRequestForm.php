<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

class FillRequestForm extends Model
{
    /**
     * @var int
     */
    private static $fillRequestId;

    protected static $entityUri = 'fill_request';

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

    public function rules()
    {
        return [
            'document_id' => 'integer',
            'name' => 'string',
            'email' => 'email',
            'date' => 'integer',
            'ip' => 'ip',
        ];
    }

    public static function init(PDFfiller $client, $fillRequestId)
    {
        self::setFillRequestId($fillRequestId);
        parent::init($client);
    }

    public static function setFillRequestId($fillRequestId)
    {
        self::$fillRequestId = $fillRequestId;
    }

    public static function getFillRequestId()
    {
        return self::$fillRequestId;
    }

    protected static function getUri()
    {
        $fillRequestId = static::getFillRequestId();
        if (!$fillRequestId) {
            throw new \InvalidArgumentException('Invalid request id.
            Note that FillRequestForm::init() must be performed before call any requests.');
        }
        return static::getEntityUri() . '/' . $fillRequestId . '/filled_form/';
    }

    public function export()
    {
        return static::query($this->id, 'export');
    }

    public function download()
    {
        return static::query($this->id, 'download');
    }
}
