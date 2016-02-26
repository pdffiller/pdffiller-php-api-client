<?php

namespace PDFfiller\OAuth2\Client\Provider;

class BaseEntity
{
    /**
     * @var PDFfiller
     */
    public $client;

    /**
     * SignatureRequest constructor.
     */
    public function __construct(PDFfiller $client)
    {
        $this->client = $client;
    }
}
