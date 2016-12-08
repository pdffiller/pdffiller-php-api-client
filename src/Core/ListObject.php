<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use PDFfiller\OAuth2\Client\Provider\Contracts\Arrayable;
use ArrayAccess;

/**
 * Class ListObject
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class ListObject implements ArrayAccess, Arrayable
{
    /** @var array */
    protected $items = [];

    /**
     * ListObject constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * Returns array representation of an object.
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($entry) {
            if ($entry instanceof Arrayable) {
                return $entry->toArray();
            }

            return $entry;
        }, $this->items);
    }

    /**
     * Checks if the element with given key exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Returns the element by given key.
     * Returns null if value does not exist.
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        if (isset($this->items[$offset])) {
            return $this->items[$offset];
        }

        return null;
    }

    /**
     * Sets the element on given key.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Removes element from list
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
