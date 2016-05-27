<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use Illuminate\Validation\Validator;
use PDFfiller\OAuth2\Client\Provider\Exceptions\IdMissingException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidQueryException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidRequestException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ValidationException;
use PDFfiller\Validation\Rules;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use Symfony\Component\Translation\Translator;

/**
 * Class Model
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $id
 */
abstract class Model
{
    const RULES_KEY = false;
    const USER_AGENT = 'pdffiller-php-api-client/1.1.0';

    protected static $entityUri = null;
    /**
     * @var PDFfiller
     */
    protected $client = null;

    /**
     * Model constructor.
     * @param array $array
     */

    /**
     * @var array cached attributes
     */
    private $oldValues = [];

    /**
     * @var array attributes
     */
    protected $attributes = ['id'];

    protected $validationErrors;

    public function __construct($provider, $array = [])
    {
        $this->client = $provider;
        $this->parseArray($array);
    }

    public abstract  function attributes();

    public function rules($key = null)
    {
        if ($key) {
            return Rules::get($key);
        }

        if (static::RULES_KEY) {
            return Rules::get(static::RULES_KEY);
        }

        return [];
    }

    protected static function getUri()
    {
        return static::getEntityUri() . '/';
    }

    /**
     * @param bool $newRecord
     * @param bool $validate
     * @param array $options
     * @return mixed
     * @throws ValidationException
     */
    public function save($newRecord = true, $validate = true, $options = [])
    {
        if ($validate && !$this->validate()) {
            throw new ValidationException($this->validationErrors);
        }

        if ($newRecord) {
            return $this->create($options);
        }

        return $this->update($options);
    }

    /**
     * @param array $options can hold next options: only || except
     * @return array
     */
    public function toArray($options = [])
    {
        $allowed = $this->getAttributes();
        $props = get_object_vars($this);
        !isset($options['except']) && $options['except'] = [];

        if (isset($options['only'])) {
            foreach ($options['only'] as $ndx => $option) {
                if (!in_array($option, $allowed)) {
                    unset($options['only'][$ndx]);
                }

                $allowed = $options['only'];
            }
        } else {
            foreach ($allowed as $ndx => $value) {
                if (in_array($value, $options['except'])) {
                    unset($allowed[$ndx]);
                }
            }
        }

        foreach ($props as $key => $value) {
            if (!in_array($key, $allowed)) {
                unset($props[$key]);
            } elseif (is_object($value) && method_exists($value, 'toArray')){
                $props[$key] = $value->toArray();
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
            $this->{$key} = $value;
        }

        return $this;
    }

    protected function cacheFields($properties)
    {
        $this->oldValues = $properties;
    }

    public function validate($key = null)
    {
        $values = $this->toArray();
        $rules = array_merge(['id' => 'integer'], $this->rules($key));
        $validator = $this->getValidator($values, $rules);
        $passes = $validator->passes();
        $this->validationErrors = $validator->errors();

        return $passes;
    }

    /**
     * @param $values
     * @param $rules
     * @param $locale
     * @return Validator
     */
    protected function getValidator($values, array $rules, $locale = 'en_US')
    {
        return new Validator(new Translator($locale), $values, $rules);
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
            if (is_array($entities)){
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
        $params = $this->toArray($options);
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
        $params = $this->toArray($options);
        $diff = $this->findDiff($this->oldValues, $params);
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
     * @return array entities list
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
            $instance = new static($provider, $params);
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
     * @param array $old old values
     * @param array $new new values
     * @return array all new or changed values
     */
    private function findDiff($old, $new)
    {
        $diff = [];
        foreach ($new as $key => $value) {
            if (!isset($old[$key]) || $old[$key] !== $new[$key]) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }

    private function getAttributes()
    {
        return array_merge($this->attributes, $this->attributes());
    }

    public function __get($name)
    {
        if (in_array($name, $this->getAttributes()) && isset($this->{$name})) {
            return $this->{$name};
        } elseif (method_exists($this, $method = 'get' . ucfirst($name))) {
            return $this->{$method}();
        }

        return null;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->getAttributes())) {
            $this->{$name} = $value;
        } elseif (method_exists($this, $method = 'set' . ucfirst($name))) {
            $this->{$method}($value);
        }
    }
}
