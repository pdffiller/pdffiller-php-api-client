<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use PDFfiller\OAuth2\Client\Provider\Contracts\Stringable;

/**
 * Class Enum
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
abstract class Enum implements Stringable
{
    const __default = null;

    private $value = self::__default;

    /**
     * Enum constructor.
     *
     * @param null $value
     */
    public function __construct($value = self::__default)
    {
        $constants = $this->getConstants();

        if (!in_array($value, $constants)) {
            throw new \InvalidArgumentException("Value must be one of class constants:");
        }

        $this->value = $value;
    }

    /**
     * Returns an array of possible class values.
     *
     * @param bool $includeDefault
     * @return array
     */
    public function getConstants(bool $includeDefault = false): array
    {
        $constants = (new \ReflectionClass($this))->getConstants();

        if (!$includeDefault) {
            unset($constants['__default']);
        }

        return $constants;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
