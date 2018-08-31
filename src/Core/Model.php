<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use Inflect\Inflect;
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

    /** @var string  */
    protected $primaryKey = 'id';

    /** @var array */
    protected $mapper = [];

    /** @var string  */
    protected static $entityUri = null;

    /** @var PDFfiller */
    protected $client = null;

    /** @var array cached attributes */
    private $oldValues = [];

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
     * @throws \ReflectionException
     */
    public function __construct(PDFfiller $provider, array $array = [])
    {
        if (isset($array['exists'])) {
            $this->exists = $array['exists'];
            unset($array['exists']);
        }

        $except = isset($array['except']) && is_array($array['except']) ? $array['except'] : [];
        $this->initArrayFields($except);
        $this->client = $provider;
        $this->parseArray($array);
    }

    /**
     * Initializes the object's arrays and lists
     * @param array $except
     * @throws \ReflectionException
     */
    protected function initArrayFields(array $except = [])
    {
        $reflection = new ReflectionClass(static::class);
        $docs = ($reflection->getDocComment());
        $docs = preg_replace("~[*/]+~", ' ', $docs);
        preg_match_all("~@property\s+(array|mixed|ListObject|FillableFieldsList)\s+\\$(.*)\r?\n+~", $docs, $result);

        if ($result) {
            $fields = array_diff($result[2], $except);

            foreach ($fields as $field) {
                $this->properties[$field] = new ListObject();
            }
        }
    }

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
     * @param array $options
     * @return mixed
     * @throws InvalidRequestException
     * @throws ResponseException
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
    public function toArray($options = []): array
    {
        foreach ($this->properties as $key => $value) {
            if ($value instanceof Arrayable) {
                $properties[$key] = $value->toArray();
            } elseif ($value instanceof Stringable) {
                $properties[$key] = $value->__toString();
            } else {
                $properties[$key] = $value;
            }
        }

        return $properties ?? [];
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
        $options['except'] = $options['except'] ?? [];

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
     * Builds the full url
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
     * @param $provider
     * @param array $entities
     * @param array $params
     * @return mixed
     * @throws InvalidQueryException
     * @throws InvalidRequestException
     */
    public static function query($provider, $entities = [], $params = [])
    {
        $url = self::resolveFullUrl($entities, $params);

        return static::apiCall($provider, 'query', $url);
    }

    /**
     * Returns a result of post request
     * @param $provider
     * @param $uri
     * @param array $params
     * @return mixed
     * @throws InvalidRequestException
     */
    public static function post($provider, $uri, $params = [])
    {
        return static::apiCall($provider, 'post', $uri, $params);
    }

    /**
     * Returns a result of put request
     * @param $provider
     * @param $uri
     * @param array $params
     * @return mixed
     * @throws InvalidRequestException
     */
    public static function put($provider, $uri, $params = [])
    {
        return static::apiCall($provider, 'put', $uri, $params);
    }

    /**
     * Returns a result of delete request
     * @param $provider
     * @param $uri
     * @return mixed
     * @throws InvalidRequestException
     */
    public static function delete($provider, $uri)
    {
        return static::apiCall($provider, 'delete', $uri);
    }

    /**
     * Creates model
     * @param array $options
     * @return mixed
     * @throws InvalidRequestException
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

        return $createResult;
    }

    /**
     * Updates instance/ Only changed fields will be updated
     * @param array $options
     * @return mixed
     * @throws InvalidRequestException
     */
    protected function update($options = [])
    {
        $params = $this->prepareFields($options);
        $diff = $this->findDiff($params);
        $uri = static::getUri() . $this->{$this->primaryKey};

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
     * @throws IdMissingException
     * @throws InvalidRequestException
     */
    public function remove()
    {
        if (isset($this->properties[$this->primaryKey])) {
            $this->exists = false;
            return static::deleteOne($this->client, $this->{$this->primaryKey});
        }

        throw new IdMissingException();
    }

    /**
     * Removes entity by id
     * @param $provider
     * @param $id
     * @return mixed
     * @throws InvalidRequestException
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
     * @throws InvalidQueryException
     * @throws InvalidRequestException
     * @throws \ReflectionException
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
     * @return ModelsList
     * @throws InvalidQueryException
     * @throws InvalidRequestException
     * @throws \ReflectionException
     */
    public static function all(PDFfiller $provider, array $queryParams = [])
    {
        $paramsArray = static::query($provider, null, $queryParams);
        $paramsArray['items'] = static::formItems($provider, $paramsArray);

        return new ModelsList($paramsArray);
    }

    /**
     * Unwrap the instances from response
     * @param $provider
     * @param $array
     * @return array
     * @throws \ReflectionException
     */
    protected static function formItems($provider, $array)
    {
        $set = [];

        foreach ($array['items'] as $params) {
            $instance = new static($provider, array_merge($params, ['exists' => true]));
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

        if (isset(static::$entityUri))  {
            return static::$entityUri;
        }

        $parts = explode('\\', static::class);
        return
            strtolower(
                preg_replace('/([^A-Z])([A-Z])/', "$1_$2",
                    Inflect::pluralize(end($parts))
                )
            );
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
