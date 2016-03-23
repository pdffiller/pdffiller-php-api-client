<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class SignatureRequest
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $document_id
 * @property string $method
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
    const RULES_KEY = 'signatureRequest|recipient';

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
        ];
    }

    public function __construct($provider, $array = [])
    {
        if (isset($array['recipients'])) {
            $recipients = [];
            foreach ($array['recipients'] as $recipient) {
                $recipients[] = new SignatureRequestRecipient($provider, $this->id, $recipient);
            }

            unset($array['recipients']);
            $array['recipients'] = $recipients;
        }

        parent::__construct($provider, $array);
    }

    public function certificate() {
        return self::query($this->client, $this->id, self::CERTIFICATE);
    }

    public function signedDocument() {
        return self::query($this->client, $this->id, self::SIGNED_DOCUMENT);
    }

    /**
     * @param SignatureRequestRecipient $recipient
     * @return array recipient creation result
     */
    public function addRecipient($recipient)
    {
        $createResult = $recipient->save();

        if (!isset($createResult['errors'])) {
            $this->recipients[] = $recipient;
        }

        return $createResult;
    }

    /**
     * @return SignatureRequestRecipient SignatureRequestRecipient
     */
    public function createRecipient()
    {
        return new SignatureRequestRecipient($this->client, $this->id);
    }
}