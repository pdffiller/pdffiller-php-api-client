<?php

use PHPUnit\Framework\TestCase;
use \PDFfiller\OAuth2\Client\Provider\Core\Enum;

class EnumTest extends TestCase
{
    private $state = "state_value";

    private function _getEnum($state = null): Enum
    {
        if (is_null($state)) {
            $state = $this->state;
        }

        return new class($state) extends Enum {
            const TEST_STATE = "state_value";
        };
    }

    public function testEnum()
    {
        $enum = $this->_getEnum();
        $this->assertEquals($enum->getValue(), $this->state);

        $this->expectException(InvalidArgumentException::class);
        $this->_getEnum("wrong_state");
    }
}
