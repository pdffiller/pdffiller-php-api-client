<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Validator;
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
    protected static $entityUri = null;
    /**
     * @var PDFfiller
     */
    protected static $client = null;

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

    public function __construct($array = [])
    {
        $this->parseArray($array);
    }


    public function attributes()
    {
        return [];
    }

    public function rules()
    {
        return [];
    }

    /**
     * Initialize base model settings
     * @param PDFfiller $client
     * @param string|null $uri base entity uri
     */
    public static function init(PDFfiller $client, $uri = null)
    {
        self::setClient($client);
        if ($uri !== null) {
           static::setEntityUri($uri);
        }
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
     */
    public function save($newRecord = true, $validate = true, $options = [])
    {
        if ($validate && !$this->validate()) {
            throw new \InvalidArgumentException('Check properties values');
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
//        $options['except'][] = 'resource_type';

        if (isset($options['only'])) {
            $allowed = array_merge($allowed, $options['only']);
        } else {
            foreach ($allowed as &$value) {
                unset($value);
            }
        }

        foreach ($props as $key => $value) {
            if (!in_array($key, $allowed)) {
                unset($props[$key]);
            }
        }
        // nested call & remove null
//        foreach ($props as $key => $value) {
//            if (!$options['null_values'] && $value === null) {
//                unset($props[$key]);
//            } elseif ($value instanceof AbstractObject
//                || $value instanceof AbstractList) {
//                $props[$key] = $value->toArray();
//            }
//        }
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

    private function cacheFields($properties)
    {
        $this->oldValues = $properties;
    }

    public function validate()
    {
        $values = $this->toArray();
        $rules = array_merge($this->rules(), ['id' => 'integer']);
        $translator = new Translator('en_US');
        $validator = new Validator($translator, $values, $rules);
        return $validator->passes();
    }

    /**
     * Sends request by given type, uri and options.
     * @param $method
     * @param $uri
     * @param array $params
     * @return mixed
     */
    private static function apiCall($method, $uri, $params = [])
    {
        $methodName = $method . 'ApiCall';
        $client = static::getClient();
        if (method_exists($client, $methodName)) {
            return $client->{$methodName}($uri, $params);
        }
        throw new \InvalidArgumentException('Invalid request type.');
    }

    /**
     * Returns entity properties as a result of get request.
     * @param string|null $id entity id, if not given - returns list of all entities
     * @param string|null $request entity item request
     * @return mixed entity parameters
     */
    protected static function query($id = null, $request = null)
    {
        $uri = static::getUri();
        $uri .= $id ?: '';
        $uri .= $request ? '/' . $request : '';
        echo $uri;
        return static::apiCall('query', $uri);
    }

    /**
     * Returns a result of post request.
     * @param $uri
     * @param array $params
     * @return mixed
     */
    public static function post($uri, $params = [])
    {
        return static::apiCall('post', $uri, $params);
    }

    /**
     * Returns a result of put request.
     * @param $uri
     * @param array $params
     * @return mixed
     */
    public static function put($uri, $params = [])
    {
        return static::apiCall('put', $uri, $params);
    }

    /**
     * Returns a result of delete request.
     * @param $uri
     * @return mixed
     */
    public static function delete($uri)
    {
        return static::apiCall('delete', $uri);
    }

    /**
     * @param array $options
     * @return mixed
     */
    protected function create($options = [])
    {
        $params = $this->toArray($options);
        $uri = static::getUri();
        return static::post($uri, [
            'json' => $params,
        ]);
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

        return static::put($uri, [
            'json' => $diff,
        ]);
    }

    /**
     * Removes current instance entity if it has an id property.
     * @return mixed
     */
    public function remove()
    {
        if (property_exists($this, 'id')) {
            return static::deleteOne($this->id);
        }
    }

    /**
     * Removes entity by id.
     * @param $id
     * @return mixed deletion result
     */
    public static function deleteOne($id)
    {
        $uri = static::getUri() . $id;
        return static::delete($uri);
    }

    /**
     * Returns model instance.
     * @param $id
     * @return static
     */
    public static function one($id)
    {
        $params = static::query($id);
        $instance = new static($params);
        $instance->cacheFields($params);
        return $instance;
    }

    /**
     * Returns a list of entities
     * @return array entities list
     */
    public static function all($provider = null)
    {
        $provider ? static::setClient($provider) : false ;

        $paramsArray = static::query();
        $set = [];

        foreach ($paramsArray['items'] as $params) {
            $instance = new static($params);
            $instance->cacheFields($params);
            $set[] = $instance;
        }

        return $set;
    }

    /**
     * @return PDFfiller
     */
    public static function getClient()
    {
        return static::$client;
    }

    /**
     * @param PDFfiller $client
     */
    public static function setClient(PDFfiller $client)
    {
        static::$client = $client;
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
            if (is_array($value)) {
                isset($old[$key]) && $temp = $this->findDiff($old[$key], $new[$key]);
                if ($temp) {
                    $diff[$key] = $temp;
                }
            } elseif (!isset($old[$key]) || $old[$key] !== $new[$key]) {
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
        if (in_array($name, $this->getAttributes())) {
            return $this->{$name};
        }
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->getAttributes())) {
            $this->{$name} = $value;
        }
    }
}