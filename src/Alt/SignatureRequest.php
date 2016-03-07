<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 03.03.16
 * Time: 16:47
 */

namespace PDFfiller\OAuth2\Client\Provider\Alt;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class SignatureRequest
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property string $method
 * @property string $envelope_name
 * @property string $security_pin
 * @property string $sign_in_order
 * @property array $recipients
 */
class SignatureRequest extends Model
{
    /**
     * @var string
     */
    protected static $entityUri = 'signature_request';

    public function attributes()
    {
        return [
            'method',
            'envelope_name',
            'security_pin',
            'sign_in_order',
            'recipients',
        ];
    }

    public function certificate($id) {
        return self::query($id, 'certificate');
    }

    public function signedDocument($id) {
        return self::query($id, 'signed_document');
    }
}