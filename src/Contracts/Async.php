<?php

namespace PDFfiller\OAuth2\Client\Provider\Contracts;

/**
 * Interface async
 * @package PDFfiller\OAuth2\Client\Provider\Contracts
 */
interface Async
{
    const HTTP_STATUS_CODE_READY = 200;

    const READY = true;

    const NOT_READY = false;

    public function isReady(): bool;
}
