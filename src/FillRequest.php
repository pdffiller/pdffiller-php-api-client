<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class FillRequest
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $document_id
 * @property string $access
 * @property string $status
 * @property boolean $email_required
 * @property boolean $name_required
 * @property string $custom_message
 * @property array $notification_emails
 * @property boolean $required_fields
 * @property string $custom_logo
 *
 */
class FillRequest extends Model
{
    protected static $entityUri = 'fill_request';
    const FORMS_URI = 'filled_form';
    const RULES_KEY = 'fillRequest';

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
            'required_fields',
            'custom_logo',
        ];
    }

    public function forms()
    {
        $response = static::query($this->client, [$this->id, self::FORMS_URI]);
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
        $params = static::query($this->client, [$this->id, self::FORMS_URI, $id]);
        return new FillRequestForm($this->client, $this->id, $params);
    }
}
