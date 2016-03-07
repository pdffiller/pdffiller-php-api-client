<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class SignatureRequestRecipient
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property string $email
 * @property string $name
 * @property integer $order
 * @property string $message_subject
 * @property string $message_text
 * @property integer $date_created unix timestamp
 * @property integer $date_signed unix timestamp
 * @property string $access
 * @property array $additional_documents
 * @property boolean $require_photo
 */

class SignatureRequestRecipient extends Model
{
    /**
     * @var int
     */
    protected static $signatureRequestId = null;

    protected static $entityUri = 'signature_request';

    /**
     * @param PDFfiller $client
     * @param null|string $signatureId
     * @param null $uri
     */
    public static function init(PDFfiller $client, $signatureId, $uri = null)
    {
        self::setSignatureRequestId($signatureId);
        parent::init($client, $uri);
    }

    /**
     * @inheritdoc
     */
    protected static function getUri()
    {
        $signatureRequestId = static::getSignatureRequestId();
        if (!$signatureRequestId) {
            throw new \InvalidArgumentException('Invalid request id.
            Note that SignatureRequestRecipient::init() must be performed before call any requests.');
        }
        return static::getEntityUri() . '/' . $signatureRequestId . '/recipient/';
    }

    public function remind() {
        $uri = static::getUri();
        return static::put($uri . $this->id . '/remind');
    }

    public static function getSignatureRequestId()
    {
        return static::$signatureRequestId;
    }

    /**
     * @param int $signatureRequestId
     */
    public static function setSignatureRequestId($signatureRequestId)
    {
        static::$signatureRequestId = $signatureRequestId;
    }


}
