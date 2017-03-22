<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\DTO\EmbeddedClient;

/**
 * Class Application
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $name
 * @property string $description
 * @property string $domain
 * @property string $all_domains
 * @property string $embedded_domain
 * @property EmbeddedClient $embedded_client
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

    protected $readOnly = ['embedded_client'];

    protected $casts = [
        'embedded_client' => EmbeddedClient::class,
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
            'domain',
            'redirect_uri',
            'all_domains',
            'embedded_domain',
            'logo',
            'embedded_client'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function prepareFields($options = [])
    {
        $embeddedClient = $this->embedded_client;
        $fields = parent::prepareFields($options);

        if (!isset($embeddedClient)) {
            return $fields;
        }

        if (!isset($fields['all-domains'])) {
            $fields['all-domains'] = $embeddedClient->allow_all_domains;
        }

        if (!isset($fields['embedded-domain'])) {
            $fields['embedded-domain'] = $embeddedClient->domain;
        }

        return $fields;
    }
}
