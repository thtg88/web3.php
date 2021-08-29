<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\DynamicBytes;
use Web3\Tests\TestCase;

class DynamicBytesTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'bytes',
            'result' => true,
        ],
        [
            'value' => 'bytes[]',
            'result' => true,
        ],
        [
            'value' => 'bytes[4]',
            'result' => true,
        ],
        [
            'value' => 'bytes[][]',
            'result' => true,
        ],
        [
            'value' => 'bytes[3][]',
            'result' => true,
        ],
        [
            'value' => 'bytes[][6][]',
            'result' => true,
        ],
        [
            'value' => 'bytes32',
            'result' => false,
        ],
        [
            'value' => 'bytes8[4]',
            'result' => false,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();

        $this->solidityType = new DynamicBytes();
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
