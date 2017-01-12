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
 * @property string $all_domains
 * @property string $embedded_domain
 */
class Application extends Model
{
    /** @var string */
    protected static $entityUri = 'application';

    /** @var array */
    protected $mapper = [
        'all_domains' => 'all-domains',
        'embedded_domain' => 'embedded-domain'
    ];

    const RULES_KEY = 'application';

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'secret',
            'name',
            'description',
            'domain',
            'redirect_uri',
            'all_domains',
            'embedded_domain',
        ];
    }
}
