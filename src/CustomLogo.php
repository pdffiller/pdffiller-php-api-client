<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\Uploadable;
use PDFfiller\Validation\Rules;

/**
 * Class CustomLogo
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property $id
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 * @property $width
 * @property $height
 * @property $filesize
 */
class CustomLogo extends Model implements Uploadable
{

    protected static $entityUri = 'logo';
    const UPLOAD_URL_RULE = 'customLogoUrl';
    const UPLOAD_MULTIPART_RULE = 'customLogoMultipart';

    public function attributes()
    {
        return [
            'id',
            'created_at',
            'width',
            'height',
            'filesize',
            'logo_url',
        ];
    }

    public function save()
    {
        throw new Exception("Can't save logo, use Uploader.");
    }

    public function update()
    {
        throw new Exception("Can't update logo, delete old and upload new logo.");
    }

    public function create()
    {
        throw new Exception("Can't create logo, use Uploader.");
    }

    public static function getUrlKey()
    {
        return 'customLogoUrl';
    }

    public static function getMultipartKey()
    {
        return 'customLogoMultipart';
    }
}
