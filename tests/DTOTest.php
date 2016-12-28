<?php

use PHPUnit\Framework\TestCase;
use \PDFfiller\OAuth2\Client\Provider\Core\{ListObject, Enum, AbstractObject};
use \PDFfiller\OAuth2\Client\Provider\DTO\FillableField;

class DTOTest extends TestCase
{
    /** @var AbstractObject */
    private $object = null;

    /**
     * @before
     */
    public function setObject()
    {
        $this->object = $this->_getDTObject();
    }

    private function _getEnum(): Enum
    {
        return new class("state") extends Enum {
            const TEST_STATE = "state";
        };
    }

    private function _getDTObject(): AbstractObject
    {
        return new class extends AbstractObject {
            protected $attributes = [
                'attribute1',
                'attribute2',
                'attribute3',
                'attribute4',
                'attribute5',
            ];

            public function __construct($properties = [])
            {
                parent::__construct($properties);
            }

            public function setAttributes(array $attributes = [])
            {
                $this->attributes = $attributes;
            }

            public function setCasts(array $casts = [])
            {
                $this->casts = $casts;
            }
        };
    }

    public function testToArray()
    {
        $object = $this->object;
        $empty = $object->toArray();
        $this->assertEmpty($empty);
        $attributes = [
            'attribute1',
            'attribute2',
            'attribute3',
        ];
        $object->setAttributes($attributes);

        foreach ($attributes as $index => $attribute) {
            $object->{$attribute} = $index;
        }

        $this->assertEquals($object->toArray(), array_flip($attributes));
    }


    public function testToArrayWithObjects()
    {
        $object = $this->object;
        $expected = [];
        $attributes = [
            'attribute1',
            'attribute2',
            'attribute3',
        ];
        $object->setAttributes($attributes);

//        // with DTO
        foreach ($attributes as $attribute) {
            $dto = $this->_getDTObject();
            $object->{$attribute} = $dto;
            $expected[$attribute] = $dto->toArray();
        }
        $this->assertEquals($expected, $object->toArray());
//
        // with lists
        foreach ($attributes as $attribute) {
            $list = new ListObject([1, 2, 3]);
            $object->{$attribute} = $list;
            $expected[$attribute] = $list->toArray();
        }

        $this->assertEquals($expected, $object->toArray());

        // with enums
        foreach ($attributes as $attribute) {
            $enum = $this->_getEnum();
            $object->{$attribute} = $enum;
            $expected[$attribute] = $enum->__toString();
        }

        $this->assertEquals($expected, $object->toArray());
    }

    public function testCasts()
    {
        $casts = [
            'attribute1' => 'int',
            'attribute2' => 'bool',
            'attribute3' => 'list',
            'attribute4' => 'float',
            'attribute5' => FillableField::class,
        ];

        $this->object->setCasts($casts);

        $values = [
            'attribute1' => '2',
            'attribute2' => 'value',
            'attribute3' => ['item1', 'item2'],
            'attribute4' => '2.2',
            'attribute5' => [],
        ];


        foreach ($values as $key => $value) {
            if (!isset($casts[$key])) {
                continue;
            }

            $this->object->{$key} = $value;

            switch ($casts[$key]) {
                case "int":
                    $this->assertInternalType('int', $this->object->{$key});
                    break;
                case "float":
                    $this->assertInternalType('float', $this->object->{$key});
                    break;
                case "bool":
                    $this->assertInternalType('bool', $this->object->{$key});
                    break;
                case "list":
                    $this->assertInstanceOf(ListObject::class, $this->object->{$key});
                    break;
                default:
                    $this->assertInstanceOf($casts[$key], $this->object->{$key});
            }
        }
    }
}
