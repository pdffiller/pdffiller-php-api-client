<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use Closure;
use InvalidArgumentException;

class FillableFieldsList extends ListObject
{
    /**
     * FillableFieldsList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $fields = [];

        foreach ($items as $ndx => $item) {
            if ($item instanceof FillableField) {
                $fields[] = $item;
                continue;
            }

            if (!is_array($item)) {
                $item = [
                    'name' => $ndx,
                    'value' => (string)$item,
                ];
            }

            $fields[] = new FillableField($item);
        }

        parent::__construct($fields);
    }

    /**
     * Returns the fields names in simple array
     * @return array
     */
    private function getFields()
    {
        $fields = [];

        foreach ($this->items as $name => $field) {
            if (is_array($field) && isset($field['name'])) {
                $fields[] = $field['name'];
            } else if ($field instanceof FillableField){
                $fields[] = $field->name;
            } else if (is_string($name)){
                $fields[] = $name;
            } else {
                $fields[] = '';
            }
        }

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        $fields = $this->getFields();

        return in_array($offset, $fields);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        $fields = array_flip($this->getFields());

        if (!isset($fields[$offset])) {
            return null;
        }

        $key = $fields[$offset];

        return parent::offsetGet($key);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if ($value instanceof FillableField) {
            $offset = !is_null($value->name) ? $value->name : $offset;
        } else if (is_array($value)) {
            $value = new FillableField($value);
        } else if (is_scalar($value)) {
            $value = new FillableField(['name' => $offset, 'value' => $value]);
        }

        if (! $value instanceof FillableField) {
            throw new InvalidArgumentException('The value must be scalar, array or FillableField');
        }

        if (is_null($value->name) && !is_null($offset)) {
            $value->name = $offset;
        }

        $fields = array_flip($this->getFields());

        if (!isset($fields[$offset]) || is_null($offset)) {
            $this->items[] = $value;

            return true;
        }

        $key = $fields[$offset];
        $this->items[$key] = $value;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $fields = array_flip($this->getFields());
        $key = isset($fields[$offset]) ? $fields[$offset] : $offset;

        parent::offsetUnset($key);
    }

    /**
     * Walks through the list and calls the closure on each field that can be filled.
     *
     * @param Closure $closure
     */
    public function eachFillable(Closure $closure)
    {
        /** @var FillableField $item */
        foreach ($this->items as $item) {
            if ($item->fillable) {
                $closure($item);
            }
        }
    }

    /**
     * Returns a new list containing only fields that can be filled
     *
     * @return FillableFieldsList
     */
    public function getOnlyFillable()
    {
        $list = [];

        foreach ($this->items as $item) {
            if ($item->fillable) {
                $list[] = $item;
            }
        }

        return new static($list);
    }
}
