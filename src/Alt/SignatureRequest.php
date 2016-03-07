<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 03.03.16
 * Time: 16:47
 */

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class SignatureRequest
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
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

    public function attributes()
    {
        return [
            'method',
            'envelope_name',
            'security_pin',
            'sign_in_order',
            'recipients',
        ];
    }

    public function rules()
    {
        return [
            'method' => ['string', 'in:sendtoeach,sendtogroup', 'required'],
            'envelope_name' => ['string'],
            'status' => ['string', 'in:IN_PROGRESS,NOT_SENT,REJECTED,SENT,SIGNED'],
            'security_pin' => ['string', 'in:standard,enhanced', 'required'],
            'sign_in_order' => ['boolean'],
            'recipients' => ['array', 'required'],
            'recipients.*.email' => ['email', 'required'],
            'recipients.*.name' => ['string', 'required'],
            'recipients.*.order' => ['integer', 'required_if:sign_in_order,true'],
            'recipients.*.message_subject' => ['string', 'required'],
            'recipients.*.message_text' => ['string', 'required'],
            'recipients.*.date_created' => ['integer'],
            'recipients.*.date_signed' => ['integer'],
            'recipients.*.access' => ['string', 'in:full,signature'],
            'recipients.*.require_photo' => ['boolean'],
            'recipients.*.additional_documents' => ['array'],
            'recipients.*.additional_documents.*.document_request_notification' => ['string'],
        ];
    }

    public function certificate($id) {
        return self::query($id, 'certificate');
    }

    public function signedDocument($id) {
        return self::query($id, 'signed_document');
    }
}