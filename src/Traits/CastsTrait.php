<?php

namespace PDFfiller\OAuth2\Client\Provider\Traits;

use PDFfiller\OAuth2\Client\Provider\Core\AbstractObject;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;
use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

trait CastsTrait
{
    /** @var array  */
    protected $casts= [];

    /**
     * Casts the field
     *
     * @param $option
     * @param $value
     * @return bool|float|mixed|ListObject|string
     */
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

        if (is_array($cast)) {
            return $this->complexListCast($value, $cast);
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

    private function complexListCast($value, $castInfo)
    {
        if (count($castInfo) < 2) {
            return $this->castToList($value);
        }

        if ($castInfo[0] !== 'list_of' || !is_array($value)) {
            return $value;
        }

        $class = $castInfo[1];
        $result = new ListObject();

        foreach ($value as $entry) {
            $result[] = $this->castToObject($entry, $class);
        }

        return $result;
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

        if (in_array(Model::class, $parentClasses)) {
            return new $class($this->getClient(), $value);
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
