<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Application
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $name
 * @property string $description
 * @property string $domain
 */
class Application extends Model
{
    protected static $entityUri = 'application';
    const RULES_KEY = 'application';

    public function attributes()
    {
        return [
            'id',
            'secret',
            'name',
            'description',
            'domain'
        ];
    }
}
