<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Contracts\Types\Str;

class StrTypeTest extends TestCase
{
    /**
     * testTypes
     *
     * @var array
     */
    protected $testTypes = [
        [
            'value' => 'string',
            'result' => true,
        ], [
            'value' => 'string[]',
            'result' => true,
        ], [
            'value' => 'string[4]',
            'result' => true,
        ], [
            'value' => 'string[][]',
            'result' => true,
        ], [
            'value' => 'string[3][]',
            'result' => true,
        ], [
            'value' => 'string[][6][]',
            'result' => true,
        ],
    ];

    /**
     * solidityType
     *
     * @var \Web3\Contracts\SolidityType
     */
    protected $solidityType;

    public function setUp(): void
    {
        parent::setUp();
        $this->solidityType = new Str();
    }

    /**
     * testIsType
     *
     * @return void
     */
    public function testIsType()
    {
        $solidityType = $this->solidityType;

        foreach ($this->testTypes as $type) {
            $this->assertEquals($solidityType->isType($type['value']), $type['result']);
        }
    }
}
