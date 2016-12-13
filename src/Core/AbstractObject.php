<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;


use PDFfiller\OAuth2\Client\Provider\Traits\CastsTrait;
use PDFfiller\OAuth2\Client\Provider\Contracts\Arrayable;
use PDFfiller\OAuth2\Client\Provider\Contracts\Stringable;

abstract class AbstractObject implements Arrayable
{
    use CastsTrait;

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
            $this->{$name} = $this->castField($name, $value);
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
        $attributes = array_intersect($this->attributes, array_keys(get_object_vars($this)));

        foreach ($attributes as $attribute) {
            if (!isset($this->{$attribute})) {
                continue;
            }

            $value = $this->{$attribute};

            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            } elseif ($value instanceof Stringable) {
                $value = $value->__toString();
            }

            $array[$attribute] = $value;
        }

        return $array;
    }
}
