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
 * @property int $active_logo_id
 * @property string $notifications
 * @property boolean $reusable
 * @property array $additional_documents
 * @property array $callbacks
 * @property string $callback_url
 * @property boolean $welcome_screen
 * @property boolean $enforce_required_fields
 * @property string $document_name
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
            'notifications',
            'document_id',
            'document_name',
            'required_fields',
            'custom_logo_id',
            'reusable',
            'enforce_required_fields',
            'welcome_screen',
            'callbacks',
            'callback_url',
            'additional_documents',
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
