<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;


abstract class Object
{
    protected $attributes = [];

    public function __construct($properties)
    {
        foreach ($properties as $name => $property) {
            $this->{$name} = $property;
        }
    }

    public function __set($name, $value)
    {
        $attributes = $this->attributes;
        if (in_array($name, $attributes)) {
            $this->{$name} = $value;
        }
    }

    public function __get($name)
    {
        $attributes = $this->attributes;
        if (in_array($name, $attributes)) {
            return $this->{$name};
        }

        return null;
    }


    public function toArray()
    {
        $array = [];

        foreach ($this->attributes as $attribute)
        {
            if (isset($this->{$attribute})) {
                $array[$attribute] = $this->{$attribute};
            }
        }

        return $array;
    }
}