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
    protected $signatureRequestId = null;

    protected static $entityUri = 'signature_request';

//    /**
//     * @param PDFfiller $client
//     * @param null|string $signatureId
//     * @param null $uri
//     */
//    public static function init(PDFfiller $client, $signatureId, $uri = null)
//    {
//        self::setSignatureRequestId($signatureId);
//        parent::init($client, $uri);
//    }
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
        return static::getUri() . $this->signatureRequestId . '/recipient/';
    }
    public function remind()
    {
        $uri = $this->uri() . $this->id . '/remind';
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

    public static function one($provider, $signatureRequestId, $id)
    {
        $params = static::query($provider, $signatureRequestId, 'recipient/' . $id);
        $instance = new static($provider, $signatureRequestId, $params);
        $instance->cacheFields($params);
        return $instance;
    }

    public function save($validate = true, $options = [])
    {
        if ($validate && !$this->validate()) {
            throw new \InvalidArgumentException('Validation fail. Check properties values');
        }

        $params = $this->toArray($options);
        $uri = $this->uri();
//        dd($uri, $params);
        $createResult =  static::post($this->client, $uri, [
            'json' => ['recipients' => [$params]],
        ]);

        if (!isset($createResult['errors'])) {
            $this->cacheFields($params);
            foreach($createResult['items'][0] as $name => $property) {
                $this->__set($name, $property);
            }
        }

        return $createResult;
    }

    public static function all()
    {
        return false;
    }
}
