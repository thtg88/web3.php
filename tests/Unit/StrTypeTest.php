<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\Str;
use Web3\Tests\TestCase;

class StrTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'string',
            'result' => true,
        ],
        [
            'value' => 'string[]',
            'result' => true,
        ],
        [
            'value' => 'string[4]',
            'result' => true,
        ],
        [
            'value' => 'string[][]',
            'result' => true,
        ],
        [
            'value' => 'string[3][]',
            'result' => true,
        ],
        [
            'value' => 'string[][6][]',
            'result' => true,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();
        $this->solidityType = new Str();
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
