<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Callback
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property $document_id
 * @property $event_id
 * @property $callback_url
 */
class Callback extends Model
{
    const RULES_KEY = 'callback';
    protected static $entityUri = 'callback';

    public function attributes()
    {
        return [
            'document_id',
            'event_id',
            'callback_url'
        ];
    }
}
