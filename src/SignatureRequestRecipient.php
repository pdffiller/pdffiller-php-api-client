<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Contracts\IAdditionalDocuments;
use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\DTO\FieldsAccess;
use PDFfiller\OAuth2\Client\Provider\Enums\DocumentAccess;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestStatus;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;

/**
 * Class SignatureRequestRecipient
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property string $name
 * @property integer $order
 * @property string $message_subject
 * @property string $message_text
 * @property integer $date_created unix timestamp
 * @property integer $date_signed unix timestamp
 * @property DocumentAccess $access
 * @property ListObject $additional_documents
 * @property boolean $require_photo
 * @property SignatureRequestStatus $status
 * @property FieldsAccess $fields
 */

class SignatureRequestRecipient extends Model implements IAdditionalDocuments
{
    const RECIPIENT = 'recipient';
    const REMIND = 'remind';

    /** @var int */
    protected $signatureRequestId = null;

    /** @var string */
    protected static $entityUri = 'signature_request';

    /** @var array */
    protected $casts = [
        'status' => SignatureRequestStatus::class,
        'access' => DocumentAccess::class,
        'fields' => FieldsAccess::class,
        'additional_documents' => ['list_of', SignatureRequestAdditionalDocument::class],
    ];

    /** @var array */
    protected $readOnly = [
        'status',
        'user_id',
        'ip',
        'date_signed',
        'date_created',
    ];

    /**
     * @inheritdoc
     */
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
          'status',
          'fields',
        ];
    }

    /**
     * SignatureRequestRecipient constructor.
     * @param PDFfiller $provider
     * @param array $array
     * @param integer|string $signatureRequestId
     */
    public function __construct($provider, $array = [], $signatureRequestId = null)
    {
        if (!is_null($signatureRequestId)) {
            $this->setSignatureRequestId($signatureRequestId);
        }

        parent::__construct($provider, $array);
    }

    /**
     * Returns request URI
     *
     * @return string
     */
    protected function uri()
    {
        return static::getUri() . $this->signatureRequestId . '/' . self::RECIPIENT . '/';
    }

    /**
     * Send the remind email to recipient
     * @return mixed
     */
    public function remind()
    {
        $uri = $this->uri() . $this->id . '/' . self::REMIND;

        return static::put($this->client, $uri);
    }

    /**
     * Returns the SendToSign id
     *
     * @return int
     */
    public function getSignatureRequestId()
    {
        return $this->signatureRequestId;
    }

    /**
     * Sets the signature request id
     *
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

        $recipientData = array_filter($createResult['recipients'], function ($recipient) use ($params) {
            return $recipient['email'] == $params['email'];
        });

        return $this->parseArray(array_pop($recipientData));
    }

    /**
     * @inheritdoc
     */
    public function update($options = [])
    {
        throw new Exception("Updating instance of this items isn't supported.");
    }

    /**
     * @inheritdoc
     */
    public static function all(PDFfiller $provider = null, array $params = [])
    {
        throw new Exception("Getting list of this items isn't supported.");
    }

    /**
     * @inheritdoc
     */
    public static function one(PDFfiller $provider = null, $id = null)
    {
        throw new Exception("Getting instance of this items isn't supported. Use SignatureRequest class.");
    }

    /**
     * @inheritdoc
     */
    public function save($options = [])
    {
        throw new Exception("Saving instance of this items isn't supported. Use SignatureRequest::addRecipient().");
    }

    /**
     * @inheritdoc
     */
    public function additionalDocuments($parameters = [])
    {
        $response = static::query($this->client, [$this->id, self::ADDITIONAL_DOCUMENTS], $parameters);
        $response['items'] = array_map(function ($document) {
            return new SignatureRequestAdditionalDocument($this->client, $this->signatureRequestId, $this->id, $document);
        }, $response['items']);

        return new ModelsList($response);
    }

    /**
     * @inheritdoc
     */
    public function additionalDocument($documentId, $parameters = [])
    {
        $response = static::query($this->client, [$this->id, self::ADDITIONAL_DOCUMENTS, $documentId], $parameters);

        return new SignatureRequestAdditionalDocument($this->client, $this->signatureRequestId, $this->id, $response);
    }

    /**
     * @inheritdoc
     */
    public function downloadAdditionalDocuments($parameters = [])
    {
        return self::query($this->client, [$this->signatureRequestId, self::ADDITIONAL_DOCUMENTS_ALL, $this->id], $parameters);
    }

    /**
     * @inheritdoc
     */
    public function createAdditionalDocument($parameters = [])
    {
        return new SignatureRequestAdditionalDocument($this->client, $this->signatureRequestId, $this->id, $parameters);
    }
}
