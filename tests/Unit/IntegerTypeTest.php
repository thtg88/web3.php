<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\Integer;
use Web3\Tests\TestCase;

class IntegerTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'int',
            'result' => true,
        ],
        [
            'value' => 'int[]',
            'result' => true,
        ],
        [
            'value' => 'int[4]',
            'result' => true,
        ],
        [
            'value' => 'int[][]',
            'result' => true,
        ],
        [
            'value' => 'int[3][]',
            'result' => true,
        ],
        [
            'value' => 'int[][6][]',
            'result' => true,
        ],
        [
            'value' => 'int32',
            'result' => true,
        ],
        [
            'value' => 'int64[4]',
            'result' => true,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();
        $this->solidityType = new Integer();
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
