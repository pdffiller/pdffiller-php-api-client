<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class FillRequest
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $document_id
 * @property string $access
 * @property string $status
 * @property string $email_required
 * @property string $name_required
 * @property string $custom_message
 * @property string $notification_emails
 *
 */
class FillRequest extends Model
{
    protected static $entityUri = 'fill_request';

    public function attributes()
    {
        return [
            'access',
            'status',
            'email_required',
            'name_required',
            'custom_message',
            'notification_emails',
            'document_id',
        ];
    }

    public function rules()
    {
        return [
            'id' => 'integer',
            'access' => ['string', 'in:full,enhanced', 'required'],
            'status' => ['string', 'in:public,private', 'required'],
            'email_required' => ['boolean', 'required'],
            'name_required' => ['boolean', 'required'],
            'custom_message' => ['string', 'required'],
            'notification_emails' => ['array', 'required'],
            'notification_emails.*.email' => ['email', 'required'],
            'notification_emails.*.name' => ['string', 'required'],
            'document_id' => ['integer', 'required'],
        ];
    }
}