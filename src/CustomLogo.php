<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\Uploadable;

/**
 * Class CustomLogo
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property int $id
 * @property int $user_id
 * @property int $width
 * @property int $height
 * @property int $filesize
 */
class CustomLogo extends Model implements Uploadable
{
    protected static $entityUri = 'custom_logo';

    public function attributes()
    {
        return [
            'id',
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
