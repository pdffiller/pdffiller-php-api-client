<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Alt\FillRequestForm;

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
    const FORMS_URI = 'filled_form';

    public function attributes()
    {
        return [
            'access',
            'status',
            'email_required',
            'redirect_url',
            'name_required',
            'custom_message',
            'url',
            'notification_emails',
            'document_id',
        ];
    }

    public function rules()
    {
        return [
            'access' => ['string', 'in:full,signature'],
            'status' => ['string', 'in:public,private', 'required'],
            'email_required' => ['boolean'],
            'redirect_url' => ['url'],
            'allow_downloads' => ['boolean'],
            'name_required' => ['boolean'],
            'custom_message' => ['string'],
            'url' => ['url'],
            'notification_emails' => ['array', 'required'],
            'notification_emails.*.email' => ['email'],
            'notification_emails.*.name' => ['string'],
            'document_id' => ['integer', 'required'],
        ];
    }

    public function forms()
    {
        $response = static::query($this->client, $this->id, self::FORMS_URI);
        $forms = [];

        if (isset($response['items'])) {
            foreach ($response['items'] as $item) {
                $forms[] = new FillRequestForm($this->client, $this->id, $item);
            }
        }

        return $forms;
    }

    /**
     * @param $id
     * @return FillRequestForm
     */
    public function form($id)
    {

        $params = static::query($this->client, $this->id, self::FORMS_URI . '/' . $id);
        return new FillRequestForm($this->client, $this->id, $params);
    }
}