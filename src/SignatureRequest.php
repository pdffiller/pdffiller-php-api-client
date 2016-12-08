<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestMethod;
use PDFfiller\OAuth2\Client\Provider\Enums\SignatureRequestStatus;

/**
 * Class SignatureRequest
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $document_id
 * @property SignatureRequestMethod $method
 * @property SignatureRequestStatus $status
 * @property string $envelope_name
 * @property string $security_pin
 * @property string $sign_in_order
 * @property array $recipients
 */
class SignatureRequest extends Model
{
    /**
     * @var string
     */
    protected static $entityUri = 'signature_request';
    const CERTIFICATE = 'certificate';
    const SIGNED_DOCUMENT = 'signed_document';
    const INBOX = 'inbox';
    const DOWNLOAD = 'download';

    protected $casts = [
        'method' => SignatureRequestMethod::class,
        'status' => SignatureRequestStatus::class
    ];

    protected $readOnly = [
        'status'
    ];

    public function attributes()
    {
        return [
            'document_id',
            'method',
            'envelope_name',
            'security_pin',
            'callback_url',
            'recipients',
            'date_created',
            'date_signed',
            'sign_in_order',
        ];
    }

    public function __construct($provider, $array = [])
    {
        $this->client = $provider;

        if (isset($array['recipients'])) {
            $array['recipients'] = $this->formRecipients($array['recipients']);
        }

        parent::__construct($provider, $array);
    }

    /**
     * Return signatures request list in inbox
     *
     * @param $provider
     * @return mixed
     */
    public static function getInbox($provider)
    {
        return self::query($provider, [self::INBOX]);
    }

    /**
     * Return zip-archive of s2s inbox documents
     *
     * @param $provider
     * @param array $params
     * @return string
     */
    public static function inboxDownload($provider, $params = [])
    {
        return self::query($provider, [self::INBOX, self::DOWNLOAD], $params);
    }

    protected function formRecipients($array)
    {

        $recipients = [];
        foreach ($array as $recipient) {
            $recipients[$recipient['id']] = new SignatureRequestRecipient($this->client, $this->id, $recipient);
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
     * @param SignatureRequestRecipient $recipient
     * @return array recipient creation result
     */
    public function addRecipient(SignatureRequestRecipient $recipient)
    {
        $response = $recipient->create();

        if (isset($response['recipients'])) {
            $recipients = $this->formRecipients($response['recipients']);
            $newRecipient = array_diff_key($recipients, $this->recipients);
            $this->recipients = $recipients;
            $result = array_pop($newRecipient);
            $recipient = $result;
            return true;
        }

        return $response;
    }

    /**
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
}
