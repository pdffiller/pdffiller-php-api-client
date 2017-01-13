<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use PDFfiller\OAuth2\Client\Provider\Contracts\Arrayable;
use ArrayAccess;
use Iterator;

/**
 * Class ListObject
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class ListObject implements ArrayAccess, Arrayable, Iterator
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        if (isset($this->items[$offset])) {
            return $this->items[$offset];
        }

        return null;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return key($this->items) !== null;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        return reset($this->items);
    }
}
