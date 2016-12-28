<?php

use PHPUnit\Framework\TestCase;
use \PDFfiller\OAuth2\Client\Provider\PDFfiller;
use \PDFfiller\OAuth2\Client\Provider\Core\{ListObject, Model, Enum, AbstractObject};
use \PDFfiller\OAuth2\Client\Provider\DTO\FillableField;

class ModelTest extends TestCase
{
    /** @var Model */
    private $model = null;

    private $attributesCount = 6;

    /**
     * @before
     */
    public function setModel()
    {
        $provider = new PDFfiller([
            'urlApiDomain' => 'http://localhost',
            'urlAccessToken' => 'http://localhost/token',
        ]);

        $this->model = $this->_getModel($provider);
    }

    private function _getModel(PDFfiller $provider, $attributes = []): Model
    {
        return new class($this->attributesCount, $provider, $attributes) extends Model {

            public function __construct($attributesCount, PDFfiller $provider, array $array = [])
            {
                $this->attributes = array_map(function ($i) {
                    return 'attribute' . $i;
                }, range(1, $attributesCount));

                parent::__construct($provider, $array);
            }

            public function attributes()
            {
                return $this->attributes;
            }

            public function setCasts($casts = [])
            {
                $this->casts = $casts;
            }
        };
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
            ];

            public function __construct($properties = [])
            {
                if (empty($properties)) {
                    $properties = array_combine($this->attributes, range(1, count($this->attributes)));
                }

                parent::__construct($properties);
            }
        };
    }

    public function testToArray()
    {
        $model = $this->model;
        $empty = $model->toArray();
        $this->assertEmpty($empty);

        foreach ($model->attributes() as $attribute) {
            $model->{$attribute} = "1";
        }

        $attributes = $model->toArray();

        foreach ($model->attributes() as $attribute) {
            $this->assertArrayHasKey($attribute, $attributes);
        }
    }

    public function testToArrayWithOptions()
    {
        $model = $this->model;

        foreach ($model->attributes() as $attribute) {
            $model->{$attribute} = "value";
        }

        $only = array_slice($model->attributes(), 0, $this->attributesCount / 2);
        $except = array_slice($model->attributes(), $this->attributesCount / 2);

        $attributes = $model->toArray([
            'except' => $except
        ]);

        $this->assertEquals(array_keys($attributes), $only);

        $attributes = $model->toArray([
            'only' => $only
        ]);

        $this->assertEquals(array_keys($attributes), $only);
    }

    public function testToArrayWithObjects()
    {
        $model = $this->model;
        $expected = [];

        // with DTO
        foreach ($model->attributes() as $attribute) {
            $dto = $this->_getDTObject();
            $model->{$attribute} = $dto;
            $expected[$attribute] = $dto->toArray();
        }

        $this->assertEquals($expected, $model->toArray());

        // with lists
        foreach ($model->attributes() as $attribute) {
            $list = new ListObject([1, 2, 3]);
            $model->{$attribute} = $list;
            $expected[$attribute] = $list->toArray();
        }

        $this->assertEquals($expected, $model->toArray());

        // with enums
        foreach ($model->attributes() as $attribute) {
            $enum = $this->_getEnum();
            $model->{$attribute} = $enum;
            $expected[$attribute] = $enum->__toString();
        }

        $this->assertEquals($expected, $model->toArray());
    }

    public function testParseArray()
    {
        $initArray = array_combine($this->model->attributes(), range(1, $this->attributesCount));

        $except = [
            'attribute1',
            'attribute2',
        ];

        $expected = array_filter($initArray, function ($entry) use ($except) {
            return !in_array($entry, $except);
        }, ARRAY_FILTER_USE_KEY);

        $this->model->parseArray($initArray, [
            'except' => $except,
        ]);

        $this->assertEquals($this->model->toArray(), $expected);

        $this->model->parseArray($initArray);

        $this->assertEquals($this->model->toArray(), $initArray);
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
        $this->model->setCasts($casts);

        $values = [
            'attribute1' => '2',
            'attribute2' => 'value',
            'attribute3' => ['item1', 'item2'],
            'attribute4' => '2.2',
            'attribute5' => [],
        ];

        $this->model->parseArray($values);

        foreach ($values as $key => $value) {
            if (!isset($casts[$key])) {
                continue;
            }

            $value = $this->model->{$key};

            switch ($casts[$key]) {
                case "int":
                    $this->assertInternalType('int', $value);
                    break;
                case "float":
                    $this->assertInternalType('float', $value);
                    break;
                case "bool":
                    $this->assertInternalType('bool', $value);
                    break;
                case "list":
                    $this->assertInstanceOf(ListObject::class, $value);
                    break;
                default:
                    $this->assertInstanceOf($casts[$key], $value);
            }
        }
    }
}
