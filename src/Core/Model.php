<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use PDFfiller\OAuth2\Client\Provider\Traits\CastsTrait;
use PDFfiller\OAuth2\Client\Provider\Contracts\Arrayable;
use PDFfiller\OAuth2\Client\Provider\Contracts\Stringable;
use PDFfiller\OAuth2\Client\Provider\Exceptions\IdMissingException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidQueryException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidRequestException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use ReflectionClass;

/**
 * Class Model
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $id
 */
abstract class Model implements Arrayable
{
    use CastsTrait;

    /** @var array */
    protected $mapper = [];

    /** @var string  */
    protected static $entityUri = null;

    /** @var PDFfiller */
    protected $client = null;

    /** @var array cached attributes */
    private $oldValues = [];

    /** @var array attributes */
    protected $attributes = ['id'];

    /** @var array  */
    protected $properties = [];

    /** @var array  */
    protected $readOnly = [];

    /** @var bool */
    public $exists = false;

    /**
     * Model constructor.
     * @param PDFfiller $provider
     * @param array $array
     */
    public function __construct(PDFfiller $provider, $array = [])
    {
        if (isset($array['exists'])) {
            $this->exists = $array['exists'];
            unset($array['exists']);
        }

        $this->initArrayFields();
        $this->client = $provider;
        $this->parseArray($array);

    }

    /**
     * Initializes the object's arrays and lists
     */
    private function initArrayFields()
    {
        $reflection = new ReflectionClass(static::class);
        $docs = ($reflection->getDocComment());
        $docs = preg_replace("~[*/]+~", ' ', $docs);
        preg_match_all("~@property\s+(array|mixed|ListObject)\s+\\$(.*)\r?\n+~", $docs, $result);

        if ($result) {
            $fields = $result[2];

            foreach ($fields as $index => $field) {
                $this->properties[$field] = new ListObject();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public abstract function attributes();

    /**
     * Returns an URL of current endpoint
     *
     * @return string
     */
    protected static function getUri()
    {
        return static::getEntityUri() . '/';
    }

    /**
     * Creates or updates model
     *
     * @param array $options
     * @return mixed
     * @internal param bool $newRecord
     */
    public function save($options = [])
    {
        if (!isset($options['except'])) {
            $options['except'] = [];
        }

        $options['except'] = array_merge($options['except'], $this->readOnly);

        if (!$this->exists) {
            return $this->create($options);
        }

        return $this->update($options);
    }

    /**
     * @inheritdoc
     */
    public function toArray($options = [])
    {
        $allowed = $this->getAttributes();
        $props = $this->properties;

        !isset($options['except']) && $options['except'] = [];

        if (isset($options['only'])) {
            $allowed = array_intersect($options['only'], $allowed);
        } else {
            $allowed = array_diff($allowed, $options['except']);
        }

        foreach ($props as $key => $value) {
            if (!in_array($key, $allowed)) {
                unset($props[$key]);
                continue;
            }

            if ($value instanceof Arrayable) {
                $props[$key] = $value->toArray();
            } elseif ($value instanceof Stringable) {
                $props[$key] = $value->__toString();
            }
        }

        return $props;
    }

    /**
     * Extracts properties from array
     *
     * @param $array
     * @param array $options
     * @return $this
     */
    public function parseArray($array, $options = [])
    {
        // default options
        !isset($options['except']) && $options['except'] = [];

        foreach ($options['except'] as $value) {
            unset($array[$value]);
        }

        foreach ($array as $key => $value) {
            $this->properties[$key] = $this->castField($key, $value);
        }

        return $this;
    }

    /**
     * Caches passed fields as old fields
     * @param $properties
     */
    protected function cacheFields($properties)
    {
        $this->oldValues = $properties;
    }

    /**
     * Sends request by given type, uri and options
     * @param PDFfiller $provider
     * @param $method
     * @param $uri
     * @param array $params
     * @return mixed
     * @throws InvalidRequestException
     */
    protected static function apiCall($provider, $method, $uri, $params = [])
    {
        $methodName = $method . 'ApiCall';
        if (method_exists($provider, $methodName)) {
            return $provider->{$methodName}($uri, $params);
        }
        throw new InvalidRequestException();
    }

    /**
     * Builds
     *
     * @param array $entities
     * @param array $params
     * @return string
     * @throws InvalidQueryException
     */
    protected static function resolveFullUrl($entities = [], $params = [])
    {
        $uri = static::getUri();

        if (!empty($entities)) {
            if (is_array($entities)) {
                $entities = implode('/', $entities) . '/';
            }

            if (!is_scalar($entities)) {
                throw new InvalidQueryException();
            }

            $uri .= $entities;
        }

        if (!empty($params)) {
            $uri .= '?' . http_build_query($params);
        }

        return $uri;
    }

    /**
     * Returns entity properties as a result of get request.
     * @param PDFfiller $provider
     * @param array $entities request URI path entities:
     * ['entity1', 'entity2'] becomes {request_uri}/entity1/entity2/
     * @param array $params query parameters
     * ['param1' => 'val1', 'param2' => 'val2'] becomes ?param1=val1&param2=val2
     * @return mixed entity parameters
     * @throws InvalidQueryException
     */
    public static function query($provider, $entities = [], $params = [])
    {
        $url = self::resolveFullUrl($entities, $params);

        return static::apiCall($provider, 'query', $url);
    }

    /**
     * Returns a result of post request
     * @param PDFfiller $provider
     * @param $uri
     * @param array $params
     * @return mixed
     */
    public static function post($provider, $uri, $params = [])
    {
        return static::apiCall($provider, 'post', $uri, $params);
    }

    /**
     * Returns a result of put request
     * @param PDFfiller $provider
     * @param $uri
     * @param array $params
     * @return mixed
     */
    public static function put($provider, $uri, $params = [])
    {
        return static::apiCall($provider, 'put', $uri, $params);
    }

    /**
     * Returns a result of delete request
     * @param PDFfiller $provider
     * @param $uri
     * @return mixed
     */
    public static function delete($provider, $uri)
    {
        return static::apiCall($provider, 'delete', $uri);
    }

    /**
     * Creates model
     *
     * @param array $options
     * @return mixed
     * @throws ResponseException
     */
    protected function create($options = [])
    {
        $params = $this->prepareFields($options);
        $uri = static::getUri();
        $createResult =  static::post($this->client, $uri, [
            'json' => $params,
        ]);

        if (isset($createResult['errors'])) {
            throw new ResponseException($createResult['errors']);
        }

        $this->cacheFields($params);
        $this->exists = true;
        $object = $createResult;

        if (isset($createResult['items'])) {
            $object = $createResult['items'][0];
        }

        $this->parseArray($object);
//        foreach($object as $name => $property) {
//            $this->__set($name, $property);
//        }

        return $createResult;
    }

    /**
     * Updates instance/ Only changed fields will be updated
     * @param array $options supports 'only' or 'except' options
     * @return mixed
     */
    protected function update($options = [])
    {
        $params = $this->prepareFields($options);
        $diff = $this->findDiff($params);
        $uri = static::getUri() . $this->id;

        $updateResult = static::put($this->client, $uri, [
            'json' => $diff,
        ]);

        if (!isset($updateResult['errors'])) {
            $this->cacheFields($params);
            foreach($updateResult as $name => $property) {
                $this->__set($name, $property);
            }
        }

        return $updateResult;
    }

    /**
     * Removes current instance entity if it has an id property
     * @return mixed
     * @throws IdMissingException if object has no id
     */
    public function remove()
    {
        if (isset($this->properties['id'])) {
            $this->exists = false;
            return static::deleteOne($this->client, $this->id);
        }

        throw new IdMissingException();
    }

    /**
     * Removes entity by id
     * @param PDFfiller $provider
     * @param $id
     * @return mixed deletion result
     */
    public static function deleteOne($provider, $id)
    {
        $uri = static::getUri() . $id;

        return static::delete($provider, $uri);
    }

    /**
     * Returns model instance
     * @param PDFfiller $provider
     * @param $id
     * @return static
     */
    public static function one(PDFfiller $provider, $id)
    {
        $params = static::query($provider, $id);
        $instance = new static($provider, array_merge($params, ['exists' => true]));
        $instance->cacheFields($params);

        return $instance;
    }

    /**
     * Returns a list of entities
     * @param PDFfiller $provider
     * @param array $queryParams
     * @return ModelsList entities list
     */
    public static function all(PDFfiller $provider, array $queryParams = [])
    {
        $paramsArray = static::query($provider, null, $queryParams);
        $paramsArray['items'] = static::formItems($provider, $paramsArray);

        return new ModelsList($paramsArray);
    }

    /**
     * Unwrap the instances from response
     *
     * @param $provider
     * @param $array
     * @return array
     */
    protected static function formItems($provider, $array)
    {
        $set = [];

        foreach ($array['items'] as $params) {
            $instance = new static ($provider, array_merge($params, ['exists' => true]));
            $instance->cacheFields($params);
            if (isset($instance->id)) {
                $set[$instance->id] = $instance;
            } else {
                $set[] = $instance;
            }
        }

        return $set;
    }

    /**
     * @return PDFfiller
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param PDFfiller $client
     */
    public function setClient(PDFfiller $client)
    {
        $this->client = $client;
    }

    /**
     * @return null
     */
    public static function getEntityUri()
    {
        return static::$entityUri;
    }

    /**
     * @param null $entityUri
     */
    public static function setEntityUri($entityUri)
    {
        static::$entityUri = $entityUri;
    }

    /**
     * Find changed properties
     *
     * @param array $new new values
     * @return array all new or changed values
     */
    private function findDiff($new)
    {
        $old = $this->oldValues;
        $diff = [];

        foreach ($new as $key => $value) {
            if (!isset($old[$key]) || $old[$key] !== $new[$key]) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }

    /**
     * Merge and return available attributes of current model
     *
     * @return array
     */
    private function getAttributes()
    {
        return array_merge($this->attributes, $this->attributes());
    }

    /**
     * Magic method, gets the object fields.
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (method_exists($this, $method = 'get' . $this->snakeToCamelCase($name). 'Field')) {
            return $this->{$method}();
        }

        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        return null;
    }

    /**
     * Magic method, sets the object fields.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (method_exists($this, $method = 'set' . $this->snakeToCamelCase($name). 'Field')) {
            $this->{$method}($value);
        } else {
            $this->properties[$name] = $this->castField($name, $value);
        }
    }

    /**
     * Converts snake_cased string to camelCase
     * @param string $string
     * @param bool $smallFirst
     * @return string
     */
    protected function snakeToCamelCase($string, $smallFirst = false)
    {
        $parts = explode('_', $string);

        array_walk($parts, function(&$element) {
           $element = ucfirst($element);
        });

        $result = implode('', $parts);

        return $smallFirst ? lcfirst($result) : $result;
    }

    /**
     * Prepares fields with incorrect property name format
     *
     * @param array $options
     * @return array
     */
    protected function prepareFields($options = [])
    {
        $params = $this->toArray($options);

        if (empty($this->mapper)) {
            return $params;
        }

        foreach ($this->mapper as $modelKey => $apiKey) {
            if(array_key_exists($modelKey,$params)) {
                $params[$apiKey] = $params[$modelKey];
                unset($params[$modelKey]);
            }
        }

        return $params;
    }
}
