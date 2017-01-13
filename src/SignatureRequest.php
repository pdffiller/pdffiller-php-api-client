<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestMethod;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestSecurityPin;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestStatus;

/**
 * Class SignatureRequest
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $document_id
 * @property SignatureRequestMethod $method
 * @property SignatureRequestStatus $status
 * @property string $envelope_name
 * @property SignatureRequestSecurityPin $security_pin
 * @property string $sign_in_order
 * @property ListObject $recipients
 * @property ListObject $callbacks
 */
class SignatureRequest extends Model
{
    const CERTIFICATE = 'certificate';
    const SIGNED_DOCUMENT = 'signed_document';
    const INBOX = 'inbox';
    const DOWNLOAD = 'download';

    /**
     * @var string
     */
    protected static $entityUri = 'signature_request';

    /** @var array */
    protected $casts = [
        'method' => SignatureRequestMethod::class,
        'status' => SignatureRequestStatus::class,
        'security_pin' => SignatureRequestSecurityPin::class,
        'callbacks' => ['list_of', Callback::class],
        'recipients' => ['list_of', SignatureRequestRecipient::class],
    ];

    /** @var array */
    protected $readOnly = [
        'status',
        'date_signed',
        'date_created',
        'callbacks'
    ];

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'document_id',
            'method',
            'envelope_name',
            'security_pin',
            'callback_url',
            'callbacks',
            'recipients',
            'date_created',
            'date_signed',
            'sign_in_order',
            'status',
        ];
    }

    /**
     * SignatureRequest constructor.
     * @param PDFfiller $provider
     * @param array $array
     */
    public function __construct($provider, $array = [])
    {
        $recipients = isset($array['recipients']) ? $array['recipients'] : [];
        unset($array['recipients']);
        parent::__construct($provider, $array);
        $recipients = self::formRecipients($recipients, $this->client, $this->id);
        $this->recipients = new ListObject($recipients);
    }

    /**
     * Return signatures request list in inbox
     *
     * @param PDFfiller $provider
     * @return ModelsList
     */
    public static function getInbox(PDFfiller $provider)
    {
        $paramsArray =  self::query($provider, [self::INBOX]);
        $paramsArray['items'] = array_map(function ($entry) {
            $entry['recipients'] = [$entry['recipients']];

            return $entry;
        }, $paramsArray['items']);
        $paramsArray['items'] = static::formItems($provider, $paramsArray);

        return new ModelsList($paramsArray);
    }

    /**
     * Returns zip-archive of SendToSign inbox documents.
     * Supports a filter parameters such as 'status', 'perpage', 'datefrom',
     * 'dateto', 'order', 'orderby'. Status can be only 'signed', 'in_progress' and 'sent'
     *
     * @param $provider
     * @param array $params
     * @return string
     */
    public static function inboxDownload($provider, $params = [])
    {
        if (isset($params['status']) && $params['status'] instanceof SignatureRequestStatus) {
            $params['status'] = mb_strtolower($params['status']->getValue());
        }

        return self::query($provider, [self::INBOX, self::DOWNLOAD], $params);
    }

    /**
     * Prepares recipients array
     *
     * @param $inputRecipients
     * @param PDFfiller $provider
     * @param $signatureRequestId
     * @return array
     */
    protected static function formRecipients($inputRecipients, PDFfiller $provider, $signatureRequestId)
    {
        $recipients = [];

        foreach ($inputRecipients as $recipient) {
            $recipients[$recipient['id']] = new SignatureRequestRecipient($provider, $signatureRequestId, $recipient);
        }

        return $recipients;
    }

    /**
     * Returns certificate if document has been signed.
     * @return mixed
     */
    public function certificate() {
        return self::query($this->client, [$this->id, self::CERTIFICATE]);
    }

    /**
     * Returns a signed document.
     * @return mixed
     */
    public function signedDocument() {
        return self::query($this->client, [$this->id, self::SIGNED_DOCUMENT]);
    }

    /**
     * Add recipient
     *
     * @param SignatureRequestRecipient $recipient
     * @return SignatureRequestRecipient recipient creation result
     */
    public function addRecipient(SignatureRequestRecipient $recipient)
    {
        $this->recipients[] = $recipient->create();

        return $recipient;
    }

    /**
     * Creates and returns new recipient
     *
     * @return SignatureRequestRecipient SignatureRequestRecipient
     */
    public function createRecipient()
    {
        return new SignatureRequestRecipient($this->client, $this->id);
    }

    /**
     * Returns current signature request recipient by id.
     * @param string|integer $id
     * @return SignatureRequestRecipient|null
     */
    public function getRecipient($id)
    {
        if ($this->recipients[$id]) {
            return $this->recipients[$id];
        }

        return null;
    }

    /**
     * Returns signature request recipients list.
     * @return ListObject
     */
    public function getRecipients()
    {
        if ($this->recipients) {
            return $this->recipients;
        }

        return null;
    }

    /**
     * Returns signature request recipients list.
     *
     * @param PDFfiller $provider
     * @param integer $signatureRequestId
     * @return ListObject
     */
    public static function recipients(PDFfiller $provider, $signatureRequestId)
    {
        $recipients = self::query($provider, [$signatureRequestId, SignatureRequestRecipient::RECIPIENT]);

        return new ListObject(self::formRecipients($recipients['items'], $provider, $signatureRequestId));
    }

    /**
     * Returns current signature request recipient by id.
     *
     * @param PDFfiller $provider
     * @param $signatureRequestId
     * @param $recipientId
     * @return SignatureRequestRecipient
     */
    public static function recipient(PDFfiller $provider, $signatureRequestId, $recipientId)
    {
        $recipient = self::query($provider, [$signatureRequestId, SignatureRequestRecipient::RECIPIENT, $recipientId]);

        return new SignatureRequestRecipient($provider, $signatureRequestId, $recipient);
    }
}
