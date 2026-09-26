<?php

namespace PDFfiller\OAuth2\Client\Provider\Tests;

use PDFfiller\OAuth2\Client\Provider\Core\ListObject;
use PDFfiller\OAuth2\Client\Provider\DTO\FillableField;
use PDFfiller\OAuth2\Client\Provider\DTO\FillableFieldsList;
use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
use PHPUnit\Framework\TestCase;

class ListObjectTest extends TestCase
{
    public function testListObjectArrayAccessAndIteration(): void
    {
        $list = new ListObject(['a' => 1, 'b' => 2]);

        $this->assertTrue(isset($list['a']));
        $this->assertSame(2, $list['b']);

        $list['c'] = 3;
        unset($list['a']);

        $this->assertFalse(isset($list['a']));
        $this->assertSame(['b' => 2, 'c' => 3], iterator_to_array($list));
        $this->assertSame(['b' => 2, 'c' => 3], $list->toArray());
    }

    public function testFillableFieldsListCanBeLoadedAndUsed(): void
    {
        $list = new FillableFieldsList(['first_name' => 'John', ['name' => 'age', 'value' => '42', 'fillable' => true]]);

        $this->assertTrue(isset($list['first_name']));
        $this->assertFalse(isset($list['unknown']));
        $this->assertInstanceOf(FillableField::class, $list['age']);
        $this->assertSame('John', $list['first_name']->value);
        $this->assertCount(2, iterator_to_array($list));
    }

    public function testEnumAcceptsOnlyItsConstants(): void
    {
        $this->assertSame('password', (new GrantType(GrantType::PASSWORD_GRANT))->getValue());

        $this->expectException(\InvalidArgumentException::class);
        new GrantType('unknown_grant');
    }
}
