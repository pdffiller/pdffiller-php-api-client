<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 07.03.16
 * Time: 14:44
 */

namespace PDFfiller\OAuth2\Client\Provider\Core;


use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;

/**
 * Class Factory
 * @package PDFfiller\OAuth2\Client\Provider\Core
 *
 * @property PDFfiller $provider
 */
class Factory
{
    /**
     * @var null $provider API client
     */
    private $provider = null;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param string $name property name
     * @return null
     */
    public function __get($name)
    {
        if (array_key_exists($name, get_object_vars($this))) {
            return $this->{$name};
        }

        return null;
    }

    /**
     * @param string $name property name
     * @param mixed $value value to set
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    /**
     * @param $className
     * @param array $params
     * @return $className
     */
    public function create($className, $params = [])
    {
        if (is_subclass_of($className, Model::class)) {
            return new $className($className, $params);
        }

        $this->throwException();
    }

    /**
     * @param Model $className
     * @return array Model
     */
    public function all($className)
    {
        if (is_subclass_of($className, Model::class)) {
            return $className::all($this->provider);
        }

        $this->throwException();
    }

    /**
     * @param Model $className
     * @param $id
     * @return Model
     */
    public function one($className, $id)
    {
        if (is_subclass_of($className, Model::class)) {
            return $className::one($this->provider, $id);
        }

        $this->throwException();
    }

    /**
     * @param Model $className
     * @param $id
     * @return Model
     */
    public function deleteOne($className, $id)
    {
        if (is_subclass_of($className, Model::class)) {
            return $className::deleteOne($this->provider, $id);
        }

        $this->throwException();
    }

    private function throwException()
    {
        throw new \InvalidArgumentException('Invalid passed class, class must be a subclass of' .
            ' PDFfiller\OAuth2\Client\Provider\Core\Model');
    }
}