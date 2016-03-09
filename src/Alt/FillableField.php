<?php
/**
 * Created by PhpStorm.
 * User: srg_kas
 * Date: 09.03.16
 * Time: 12:37
 */

namespace PDFfiller\OAuth2\Client\Provider\Alt;

/**
 * Class FillableField
 * @package PDFfiller\OAuth2\Client\Provider\Alt
 *
 * @property $name
 * @property $type
 * @property $format
 * @property $initial
 * @property boolean $required
 */
class FillableField
{
    private $attributes = [
        'name',
        'type',
        'format',
        'initial',
        'required',
    ];

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