<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Core\Uploadable;
use PDFfiller\OAuth2\Client\Provider\Exceptions\TypeException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Uploader
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $file absolute file path or url
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
        if (!in_array(Uploadable::class, class_implements($class))) {
            throw new Exception("Given class must implements Uploadable interface");
        }

        if (!is_subclass_of($class, Model::class)) {
            throw new Exception("Given class must be a subclass of Model");
        }

        $this->class = $class;
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
        $class = $this->class;
        if ($this->type == self::TYPE_URL) {
            $this->validate($class::getUrlKey());
            return [
                'json' => [
                    'file' => $this->file
                ]
            ];
        }

        if ($this->type == self::TYPE_MULTIPART) {
            $this->validate($class::getMultipartKey());
            return [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($this->file, 'r')
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

    public function validate($key = null)
    {
        if (!parent::validate($key)) {
            throw new ValidationException($this->validationErrors);
        };
    }

    public function toArray($options = [])
    {
        $array = parent::toArray($options);

        if ($this->type === self::TYPE_MULTIPART) {
            $array['file'] = new File($this->file);
        }

        return $array;
    }
}