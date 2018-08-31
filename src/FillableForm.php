<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\DTO\AdditionalDocument;
use PDFfiller\OAuth2\Client\Provider\DTO\NotificationEmail;
use PDFfiller\OAuth2\Client\Provider\Enums\DocumentAccess;
use PDFfiller\OAuth2\Client\Provider\Enums\FillRequestNotifications;
use PDFfiller\OAuth2\Client\Provider\Enums\FillRequestStatus;

/**
 * Class FillRequest
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $document_id
 * @property DocumentAccess $access
 * @property FillRequestStatus $status
 * @property boolean $email_required
 * @property boolean $name_required
 * @property string $custom_message
 * @property ListObject $notification_emails
 * @property boolean $required_fields
 * @property int $active_logo_id
 * @property FillRequestNotifications $notifications
 * @property boolean $reusable
 * @property array $additional_documents
 * @property ListObject $callbacks
 * @property string $callback_url
 * @property boolean $welcome_screen
 * @property boolean $enforce_required_fields
 * @property string $document_name
 *
 */
class FillableForm extends Model
{
    protected $primaryKey = 'fillable_form_id';

    const FORMS_URI = 'filled_forms';
    const DOWNLOAD_URI = 'download';

    /** @var array */
    protected $casts =[
        'access' => DocumentAccess::class,
        'status' => FillRequestStatus::class,
        'notifications' => FillRequestNotifications::class,
        'notification_emails' => ['list_of', NotificationEmail::class],
        'callbacks' => ['list_of', Callback::class],
    ];

    /** @var array */
    protected $readOnly = [
        'callbacks'
    ];

    /**
     * Returns filled forms
     * @return array|ModelsList
     * @throws Exceptions\InvalidQueryException
     * @throws Exceptions\InvalidRequestException
     * @throws \ReflectionException
     */
    public function forms()
    {
        $response = static::query($this->client, [$this->fillable_form_id, self::FORMS_URI]);

        $forms = new ModelsList();

        if (isset($response['items'])) {
            foreach ($response['items'] as $item) {
                $forms[] = new FilledForm($this->client, $this->fillable_form_id, $item);
            }
        }
        return $forms;
    }

    /**
     * @param $filledFormId
     * @return FilledForm
     */
    public function form($filledFormId)
    {
        $params = static::query($this->client, [$this->fillable_form_id, self::FORMS_URI, $filledFormId]);
        return new FilledForm($this->client, $this->fillable_form_id, $params);
    }

    /**
     * Downloads filled forms as a ZIP archive
     *
     * @param string $callback
     * @return mixed
     * @throws Exceptions\InvalidQueryException
     * @throws Exceptions\InvalidRequestException
     */
    public function download(string $callback = "")
    {
        $parameters = [];

        if (!empty($callback) && is_string($callback)) {
            $parameters['callback_url'] = $callback;
        }

        return static::query($this->client, [$this->fillable_form_id, self::DOWNLOAD_URI], $parameters);
    }
}
