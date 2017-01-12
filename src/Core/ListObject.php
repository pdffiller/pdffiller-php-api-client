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
}
