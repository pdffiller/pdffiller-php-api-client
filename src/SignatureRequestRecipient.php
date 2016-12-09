<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Enums\DocumentAccess;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

/**
 * Class SignatureRequestRecipient
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $email
 * @property string $name
 * @property integer $order
 * @property string $message_subject
 * @property string $message_text
 * @property integer $date_created unix timestamp
 * @property integer $date_signed unix timestamp
 * @property DocumentAccess $access
 * @property array $additional_documents
 * @property boolean $require_photo
 */

class SignatureRequestRecipient extends Model
{
    /**
     * @var int
     */
    protected $signatureRequestId = null;

    protected static $entityUri = 'signature_request';
    const RECIPIENT = 'recipient';
    const REMIND = 'remind';

    public function attributes()
    {
        return [
          'email',
          'user_id',
          'status',
          'name',
          'order',
          'message_subject',
          'message_text',
          'date_created',
          'date_signed',
          'access',
          'additional_documents',
          'require_photo',
          'ip',
        ];
    }

    /**
     * SignatureRequestRecipient constructor.
     * @param $provider
     * @param integer|string $signatureRequestId
     * @param array $array
     */
    public function __construct($provider, $signatureRequestId, $array = [])
    {
        $this->signatureRequestId = $signatureRequestId;
//        static::setEntityUri(static::BASE_URI . '/' . $signatureRequestId . '/recipient' );
        parent::__construct($provider, $array);
    }

    protected function uri()
    {
        return static::getUri() . $this->signatureRequestId . '/' . self::RECIPIENT . '/';
    }
    public function remind()
    {
        $uri = $this->uri() . $this->id . '/' . self::REMIND;
        return static::put($this->client, $uri);
    }

    public function getSignatureRequestId()
    {
        return $this->signatureRequestId;
    }

    /**
     * @param int $signatureRequestId
     */
    public function setSignatureRequestId($signatureRequestId)
    {
        $this->signatureRequestId = $signatureRequestId;
    }

    /**
     * Returns created recipient info.
     * @param array $options
     * @return mixed
     * @throws ResponseException
     */
    public function create($options = [])
    {
        $params = $this->toArray($options);
        $recipients['recipients'] = [$params];

        $uri = $this->uri();
        $createResult =  static::post($this->client, $uri, [
            'json' => $recipients,
        ]);
        if (isset($createResult['errors'])) {
            throw new ResponseException($createResult['errors']);
        }

        return $createResult;
    }

    public static function all($provider = null, array $params = [])
    {
        throw new Exception("Getting list of this items isn't supported.");
    }

    public static function one($provider = null, $id = null)
    {
        throw new Exception("Getting instance of this items isn't supported. Use SignatureRequest class.");
    }

    public function update($options = [])
    {
        throw new Exception("Updating instance of this items isn't supported.");
    }

    public function save($new = true, $options = null)
    {
        throw new Exception("Saving instance of this items isn't supported. Use SignatureRequest::addRecipient().");
    }

}

