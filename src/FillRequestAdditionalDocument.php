<?php

namespace PDFfiller\OAuth2\Client\Provider;

/**
 * Class FillRequestAdditionalDocument
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $name
 * @property string $filename
 * @property string $ip
 * @property int $date_created
 */
class FillRequestAdditionalDocument extends AdditionalDocument
{
    protected static $entityUri = 'fill_request';

    /**
     * @inheritdoc
     */
    protected function getResourceIdentifier()
    {
        return 'filled_form';
    }
}
