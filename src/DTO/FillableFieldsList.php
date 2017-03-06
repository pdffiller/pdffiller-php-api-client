<?php

namespace PDFfiller\OAuth2\Client\Provider\DTO;

use PDFfiller\OAuth2\Client\Provider\Core\ListObject;

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
        if (is_array($value) || $value instanceof FillableField) {
            return parent::offsetSet($offset, $value);
        }

        $fields = array_flip($this->getFields());
        $key = null;

        if (!isset($fields[$offset])) {
            $this->items[] = new FillableField(['name' => $offset, 'value' => $value]);

            return true;
        }

        $key = $fields[$offset];
        $current = $this->items[$key];

        if (is_array($this->items[$key])) {
            $this->items[$key]['value'] = $value;
        } else if ($current instanceof FillableField) {
            $current->value = $value;
        }

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
}
