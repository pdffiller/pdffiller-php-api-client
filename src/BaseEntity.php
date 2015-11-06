<?php

namespace aslikeyou\OAuth2\Client\Provider;

class BaseEntity
{
    /**
     * @var Pdffiller
     */
    public $client;

    /**
     * SignatureRequest constructor.
     */
    public function __construct(Pdffiller $client)
    {
        $this->client = $client;
    }
}
