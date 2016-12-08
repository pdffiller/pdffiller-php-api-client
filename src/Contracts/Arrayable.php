<?php

namespace PDFfiller\OAuth2\Client\Provider\Contracts;

/**
 * Interface Arrayable
 * @package PDFfiller\OAuth2\Client\Provider\Contracts
 */
interface Arrayable
{
    /**
     * Returns array representation of given object
     *
     * @return mixed
     */
    public function toArray();
}
