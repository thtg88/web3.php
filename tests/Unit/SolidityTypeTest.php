<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Contracts\SolidityType;

class SolidityTypeTest extends TestCase
{
    protected SolidityType $type;

    public function setUp(): void
    {
        parent::setUp();
        $this->type = new SolidityType();
    }

    /** @test */
    public function nested_types(): void
    {
        $type = $this->type;

        $this->assertEquals($type->nestedTypes('int[2][3][4]'), ['[2]', '[3]', '[4]']);
        $this->assertEquals($type->nestedTypes('int[2][3][]'), ['[2]', '[3]', '[]']);
        $this->assertEquals($type->nestedTypes('int[2][3]'), ['[2]', '[3]']);
        $this->assertEquals($type->nestedTypes('int[2][]'), ['[2]', '[]']);
        $this->assertEquals($type->nestedTypes('int[2]'), ['[2]']);
        $this->assertEquals($type->nestedTypes('int[]'), ['[]']);
        $this->assertEquals($type->nestedTypes('int'), false);
    }

    /** @test */
    public function nested_name(): void
    {
        $type = $this->type;

        $this->assertEquals($type->nestedName('int[2][3][4]'), 'int[2][3]');
        $this->assertEquals($type->nestedName('int[2][3][]'), 'int[2][3]');
        $this->assertEquals($type->nestedName('int[2][3]'), 'int[2]');
        $this->assertEquals($type->nestedName('int[2][]'), 'int[2]');
        $this->assertEquals($type->nestedName('int[2]'), 'int');
        $this->assertEquals($type->nestedName('int[]'), 'int');
        $this->assertEquals($type->nestedName('int'), 'int');
    }

    /** @test */
    public function is_dynamic_array(): void
    {
        $type = $this->type;

        $this->assertFalse($type->isDynamicArray('int[2][3][4]'));
        $this->assertTrue($type->isDynamicArray('int[2][3][]'));
        $this->assertFalse($type->isDynamicArray('int[2][3]'));
        $this->assertTrue($type->isDynamicArray('int[2][]'));
        $this->assertFalse($type->isDynamicArray('int[2]'));
        $this->assertTrue($type->isDynamicArray('int[]'));
        $this->assertFalse($type->isDynamicArray('int'));
    }

    /** @test */
    public function is_static_array(): void
    {
        $type = $this->type;

        $this->assertTrue($type->isStaticArray('int[2][3][4]'));
        $this->assertFalse($type->isStaticArray('int[2][3][]'));
        $this->assertTrue($type->isStaticArray('int[2][3]'));
        $this->assertFalse($type->isStaticArray('int[2][]'));
        $this->assertTrue($type->isStaticArray('int[2]'));
        $this->assertFalse($type->isStaticArray('int[]'));
        $this->assertFalse($type->isStaticArray('int'));
    }

    /** @test */
    public function static_array_length(): void
    {
        $type = $this->type;

        $this->assertEquals($type->staticArrayLength('int[2][3][4]'), 4);
        $this->assertEquals($type->staticArrayLength('int[2][3][]'), 1);
        $this->assertEquals($type->staticArrayLength('int[2][3]'), 3);
        $this->assertEquals($type->staticArrayLength('int[2][]'), 1);
        $this->assertEquals($type->staticArrayLength('int[2]'), 2);
        $this->assertEquals($type->staticArrayLength('int[]'), 1);
        $this->assertEquals($type->staticArrayLength('int'), 1);
    }

    /** @test */
    // public function encode(): void
    // {
    //     $type = $this->type;
    //     $this->assertTrue(true);
    // }

    /** @test */
    // public function decode(): void
    // {
    //     $type = $this->type;
    //     $this->assertTrue(true);
    // }
}
