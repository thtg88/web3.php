<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\Boolean;
use Web3\Tests\TestCase;

class BooleanTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'bool',
            'result' => true,
        ],
        [
            'value' => 'bool[]',
            'result' => true,
        ],
        [
            'value' => 'bool[4]',
            'result' => true,
        ],
        [
            'value' => 'bool[][]',
            'result' => true,
        ],
        [
            'value' => 'bool[3][]',
            'result' => true,
        ],
        [
            'value' => 'bool[][6][]',
            'result' => true,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();

        $this->solidityType = new Boolean();
    }

    /** @test */
    public function is_type(): void
    {
        $solidityType = $this->solidityType;

        foreach ($this->testTypes as $type) {
            $this->assertEquals($solidityType->isType($type['value']), $type['result']);
        }
    }
}
