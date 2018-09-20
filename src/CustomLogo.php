<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Contracts\Uploadable;

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
    /** @var string */
    protected static $entityUri = 'custom_branding/custom_logo';

    /**
     * @inheritdoc
     */
    protected function create($options = [])
    {
        throw new Exception("Can't create logo, use Uploader.");
    }

    /**
     * @inheritdoc
     */
    protected function update($options = [])
    {
        throw new Exception("Can't update logo, delete old and upload new logo.");
    }

    /**
     * @inheritdoc
     */
    public function save($options = [])
    {
        throw new Exception("Can't save logo, use Uploader.");
    }
}
