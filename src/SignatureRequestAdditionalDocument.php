<?php

namespace PDFfiller\OAuth2\Client\Provider;

/**
 * Class SignatureRequestAdditionalDocument
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $name
 * @property string $filename
 * @property string $ip
 * @property int $date_created
 */
class SignatureRequestAdditionalDocument extends AdditionalDocument
{
    protected static $entityUri = 'signature_request';

    /**
     * @inheritdoc
     */
    protected function getResourceIdentifier()
    {
        return 'recipient';
    }
}
