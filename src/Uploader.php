<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Contracts\Uploadable;
use PDFfiller\OAuth2\Client\Provider\Exceptions\TypeException;

/**
 * Class Uploader
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $file absolute file path or url
 * @property string $type
 */
class Uploader extends Model
{
    const TYPE_URL = "url";
    const TYPE_MULTIPART = "multipart";

    /** @var string */
    protected static $entityUri = 'document';

    /** @var array|null */
    protected $class = null;

    /** @var array */
    protected $attributesAdditional = [];

    /**
     * Uploader constructor.
     * @param PDFfiller $provider
     * @param array $class
     * @param array $array
     * @throws Exception
     */
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

    /**
     * Sets additional attributes
     *
     * @param $attributes
     */
    public function setAdditionalAttributes($attributes) {
        $this->attributesAdditional = $attributes;
    }

    /**
     * Returns additional attributes
     *
     * @return array
     */
    public function getAdditionalAttributes() {
        return $this->attributesAdditional;
    }

    /**
     * Prepares upload request parameters
     *
     * @return array|null
     */
    public function getUploadParams()
    {
        if ($this->type === self::TYPE_URL) {
            return [
                'json' => array_merge($this->getAdditionalAttributes(), ['file' => $this->file]),
            ];
        }

        if ($this->type === self::TYPE_MULTIPART) {
            $params[] = [
                'name' => 'file',
                'contents' => fopen($this->file, 'r'),
            ];

            foreach ($this->getAdditionalAttributes() as $key => $value) {
                $params[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }

            return [
                'multipart' => $params
            ];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Uploads file and returns the model
     *
     * @return null|Model
     */
    public function upload()
    {
        $params = $this->getUploadParams();

        if ($params) {
            $class = $this->class;
            $uri = $class::getUri();
            $document = static::post($this->client, $uri, $params);

            /** @var Model $instance */
            $instance = new $this->class($this->client, $document);
            $instance->exists = true;
            $instance->cacheFields($document);
            return $instance;
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
