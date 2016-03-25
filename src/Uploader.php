<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Exceptions\TypeException;

/**
 * Class Uploader
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string|resource $file
 * @property string $type
 */
class Uploader extends Model
{
    protected static $entityUri = 'document';
    const TYPE_URL = "url";
    const TYPE_MULTIPART = "multipart";
    /**
     * @var Model|null
     */
    protected $class = null;

    protected $attributes = [
        'file',
    ];

    public function __construct($provider, $class, array $array = [])
    {
        if (class_exists($class) && is_subclass_of($class, Model::class)) {
            $this->class = $class;
        }
        parent::__construct($provider, $array);
    }

    public function attributes()
    {
        return $this->attributes;
    }

    public function upload()
    {
        $params = $this->checkUploadType();
        if ($params) {
            $class = $this->class;
            $uri = $class::getUri();
            $document = static::post($this->client, $uri, $params);

            /** @var Model $instance */
            $instance = new $this->class($this->client, $document);
            $instance->cacheFields($document);
            return $instance;
        }

        return null;
    }

    /** Checks upload types and returns request array
     * @return array
     */
    private function checkUploadType()
    {
        if ($this->type == self::TYPE_URL) {
            return [
                'json' => [
                    'file' => $this->file
                ]
            ];
        }

        if ($this->type == self::TYPE_MULTIPART) {
            return [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => $this->file
                    ],
                ]
            ];
        }

        return null;
    }

    /**
     * @param string $type must be "url" or "multipart", use Uploader::TYPE_URL and Uploader::TYPE_MULTIPART
     * @throws TypeException
     */
    public function setType($type)
    {
        if ($type != self::TYPE_MULTIPART && $type !=self::TYPE_URL) {
            throw new TypeException([self::TYPE_MULTIPART, self::TYPE_URL]);
        }

        $this->type = $type;
    }

}