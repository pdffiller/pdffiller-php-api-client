<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 07.03.16
 * Time: 14:44
 */

namespace PDFfiller\OAuth2\Client\Provider\Core;


use PDFfiller\OAuth2\Client\Provider\Alt\FillRequest;

class Factory
{
    /**
     * @var null $provider API client
     */
    private $provider = null;

    public function __construct($provider)
    {
        $this->setProvider($provider);
    }

    /**
     * @return null
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param null $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    public function create($className, $params = [])
    {
        return new $className($className, $params);
    }

    /**
     * @param Model $className
     * @return mixed
     */
    public function all($className)
    {
        return $className::all($this->getProvider());
    }
}