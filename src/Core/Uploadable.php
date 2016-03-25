<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

/**
 * Interface Uploadable
 * @package PDFfiller\OAuth2\Client\Provider\Core
 *
 * @property $urlKey
 * @property $multipartKey
 */
interface Uploadable
{
    public static function getUrlKey();
    public static function getMultipartKey();
}