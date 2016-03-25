<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

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
class CustomLogo extends Model
{

    protected static $entityUri = 'logo';
    const RULES_KEY = 'logo';

    public function attributes()
    {
        return [
            'id',
            'user_id',
            'created_at',
            'updated_at',
            'width',
            'height',
            'filesize',
        ];
    }

    public static function uploadViaUrl($provider, $url)
    {
        return static::upload($provider, self::urlToBase64($url));
    }

    public function uploadViaMultipart($provider, $fopenResource)
    {
        return $this->upload($provider, self::fileToBase64($fopenResource));
    }

    protected static function upload($provider, $data)
    {
        $uri = static::getUri();
        $document = static::post($provider, $uri, [
            'form_params' => [
                'content' => $data
            ]
        ]);
        /** @var Model $instance */
        $instance = new static($provider, $document);
        $instance->cacheFields($document);

        return $instance;
    }

    public function save()
    {
        throw new Exception("Can't save logo, use upload method.");
    }

}
