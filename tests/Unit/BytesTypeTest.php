<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\Bytes;
use Web3\Tests\TestCase;

class BytesTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'bytes',
            'result' => false,
        ],
        [
            'value' => 'bytes[]',
            'result' => false,
        ],
        [
            'value' => 'bytes[4]',
            'result' => false,
        ],
        [
            'value' => 'bytes[][]',
            'result' => false,
        ],
        [
            'value' => 'bytes[3][]',
            'result' => false,
        ],
        [
            'value' => 'bytes[][6][]',
            'result' => false,
        ],
        [
            'value' => 'bytes32',
            'result' => true,
        ],
        [
            'value' => 'bytes8[4]',
            'result' => true,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();
        $this->solidityType = new Bytes();
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
