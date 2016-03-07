<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;


use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Callback
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property $document_id
 * @property $event_id
 * @property $callback_url
 */
class Callback extends Model
{
    protected static $entityUri = 'callback';

    protected $attributes = ['document_id', 'event_id', 'callback_uri'];

}
