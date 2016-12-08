<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;


use PDFfiller\OAuth2\Client\Provider\Contracts\Arrayable;

abstract class AbstractObject implements Arrayable
{
    protected $attributes = [];

    /**
     * AbstractObject constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        foreach ($properties as $name => $property) {
            $this->{$name} = $property;
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $attributes = $this->attributes;
        if (in_array($name, $attributes)) {
            $this->{$name} = $value;
        }
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        $attributes = $this->attributes;
        if (in_array($name, $attributes)) {
            return $this->{$name};
        }

        return null;
    }

    /**
     * Returns array representation of an object.
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach ($this->attributes as $attribute) {
            if (isset($this->{$attribute})) {
                $array[$attribute] = $this->{$attribute};
            }
        }

        return $array;
    }
}
