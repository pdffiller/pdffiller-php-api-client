<?php

namespace PDFfiller\OAuth2\Client\Provider\Traits;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;
use PDFfiller\OAuth2\Client\Provider\Core\ListObject;

trait CastsTrait
{
    /** @var array  */
    protected $casts= [];

    private function castField($option, $value)
    {
        $casts = $this->casts;

        if (!isset($casts[$option])) {
            return $value;
        }

        $cast = $casts[$option];

        if (is_null($value) || is_null($cast)) {
            return $value;
        }

        switch ($cast) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'list':
                return $this->castToList($value);
            default:
                return $this->castToObject($value, $cast);
        }
    }

    /**
     * Casts value to the given class
     *
     * @param $value
     * @param $class
     * @return mixed
     */
    private function castToObject($value, $class)
    {
        if (!class_exists($class) || $value instanceof $class) {
            return $value;
        }

        $parentClasses = class_parents($class);

        if (in_array(Enum::class, $parentClasses) || in_array(AbstractObject::class, $parentClasses)) {
            return new $class($value);
        }

        return $value;
    }

    /**
     * Casts value to the list

     * @param $value
     * @return ListObject
     */
    private function castToList($value)
    {
        if ($value instanceof ListObject) {
            return $value;
        }

        return new ListObject((array)$value);
    }
}
