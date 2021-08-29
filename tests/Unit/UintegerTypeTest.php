<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\Uinteger;
use Web3\Tests\TestCase;

class UintegerTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'uint',
            'result' => true,
        ],
        [
            'value' => 'uint[]',
            'result' => true,
        ],
        [
            'value' => 'uint[4]',
            'result' => true,
        ],
        [
            'value' => 'uint[][]',
            'result' => true,
        ],
        [
            'value' => 'uint[3][]',
            'result' => true,
        ],
        [
            'value' => 'uint[][6][]',
            'result' => true,
        ],
        [
            'value' => 'uint32',
            'result' => true,
        ],
        [
            'value' => 'uint64[4]',
            'result' => true,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();

        $this->solidityType = new Uinteger();
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
