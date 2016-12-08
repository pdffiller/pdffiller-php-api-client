<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Enums\CallbackEvent;

/**
 * Class Callback
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property int $document_id
 * @property CallbackEvent $event_id
 * @property string $callback_url
 */
class Callback extends Model
{
    protected static $entityUri = 'callback';

    protected $casts = [
        'event_id' => CallbackEvent::class,
    ];

    public function attributes()
    {
        return [
            'document_id',
            'event_id',
            'callback_url'
        ];
    }
}
