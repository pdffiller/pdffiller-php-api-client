<?php

namespace PDFfiller\OAuth2\Client\Provider\Grant;

use League\OAuth2\Client\Grant\Password;

class InternalGrant extends Password
{
    const NAME = 'internal';
    protected function getName()
    {
        return self::NAME;
    }
}