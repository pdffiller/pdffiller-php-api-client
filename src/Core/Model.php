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

    const USER_AGENT = 'pdffiller-php-api-client/1.1.0';

    /**
     * Maps model field to request field
     * @var array
     */
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

    /**
     * Model constructor.
     * @param PDFfiller $provider
     * @param array $array
     */
    public function __construct(PDFfiller $provider, $array = [])
    {
        $this->initArrayFields();
        $this->client = $provider;
        $this->parseArray($array);
    }

    /**
     * Initialize the object's arrays and lists.
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
     * Returns an array of allowed attributes
     * @return mixed
     */
    public abstract function attributes();

    /**
     * Returns an URL of current endpoint.
     *
     * @return string
     */
    protected static function getUri()
    {
        return static::getEntityUri() . '/';
    }

    /**
     * @param bool $newRecord
     * @param array $options
     * @return mixed
     */
    public function save($newRecord = true, $options = [])
    {
        if (!isset($options['except'])) {
            $options['except'] = [];
        }

        $options['except'] = array_merge($options['except'], $this->readOnly);

        if ($newRecord) {
            return $this->create($options);
        }

        return $this->update($options);
    }

    /**
     * Returns array representation of an object
     *
     * @param array $options can hold next options: only || except
     * @return array
     */
    public function toArray($options = [])
    {
        $allowed = $this->getAttributes();
//        $props = get_object_vars($this);
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
     * Caches passed fields as old fields.
     * @param $properties
     */
    protected function cacheFields($properties)
    {
        $this->oldValues = $properties;
    }

    /**
     * Sends request by given type, uri and options.
     * @param PDFfiller $provider
     * @param $method
     * @param $uri
     * @param array $params
     * @return mixed
     * @throws InvalidRequestException
     */
    protected static function apiCall($provider, $method, $uri, $params = [])
    {
        $params['headers']['User-Agent'] = self::USER_AGENT;
        $methodName = $method . 'ApiCall';
        if (method_exists($provider, $methodName)) {
            return $provider->{$methodName}($uri, $params);
        }
        throw new InvalidRequestException();
    }

    /**
     * Returns entity properties as a result of get request.
     * @param PDFfiller $provider
     * @param array $entities entities items for request:
     * ['entity1', 'entity2'] becomes {request_uri}/entity1/entity2/
     * @param array $params query parameters
     * ['param1' => 'val1', 'param2' => 'val2'] becomes ?param1=val1&param2=val2
     * @return mixed entity parameters
     * @throws InvalidQueryException
     */
    protected static function query($provider, $entities = [], $params = [])
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

        return static::apiCall($provider, 'query', $uri);
    }

    /**
     * Returns a result of post request.
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
     * Returns a result of put request.
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
     * Returns a result of delete request.
     * @param PDFfiller $provider
     * @param $uri
     * @return mixed
     */
    public static function delete($provider, $uri)
    {
        return static::apiCall($provider, 'delete', $uri);
    }

    /**
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
        $object = $createResult;

        if (isset($createResult['items'])) {
            $object = $createResult['items'][0];
        }

        foreach($object as $name => $property) {
            $this->__set($name, $property);
        }

        return $createResult;
    }

    /**
     * Updates instance changed fields.
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
     * Removes current instance entity if it has an id property.
     * @return mixed
     * @throws IdMissingException if object has no id
     */
    public function remove()
    {
        if (property_exists($this, 'id')) {
            return static::deleteOne($this->client, $this->id);
        }

        throw new IdMissingException();
    }

    /**
     * Removes entity by id.
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
     * Returns model instance.
     * @param PDFfiller $provider
     * @param $id
     * @return static
     */
    public static function one($provider, $id)
    {
        $params = static::query($provider, $id);
        $instance = new static($provider, $params);
        $instance->cacheFields($params);

        return $instance;
    }

    /**
     * Returns a list of entities
     * @param PDFfiller $provider
     * @param array $queryParams
     * @return ModelsList entities list
     */
    public static function all($provider, array $queryParams = [])
    {
        $paramsArray = static::query($provider, null, $queryParams);
        $paramsArray['items'] = static::formItems($provider, $paramsArray);

        return new ModelsList($paramsArray);
    }

    protected static function formItems($provider, $array)
    {
        $set = [];

        foreach ($array['items'] as $params) {
            $instance = new static ($provider, $params);
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
     * Find changed properties.
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

        if (in_array($name, $this->getAttributes()) && isset($this->properties[$name])) {
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
        } elseif (in_array($name, $this->getAttributes())) {
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
