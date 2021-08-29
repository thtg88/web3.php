<?php

namespace Web3\Tests\Unit;

use Web3\Contracts\SolidityType;
use Web3\Contracts\Types\Address;
use Web3\Tests\TestCase;

class AddressTypeTest extends TestCase
{
    protected array $testTypes = [
        [
            'value' => 'address',
            'result' => true,
        ],
        [
            'value' => 'address[]',
            'result' => true,
        ],
        [
            'value' => 'address[4]',
            'result' => true,
        ],
        [
            'value' => 'address[][]',
            'result' => true,
        ],
        [
            'value' => 'address[3][]',
            'result' => true,
        ],
        [
            'value' => 'address[][6][]',
            'result' => true,
        ],
    ];

    protected SolidityType $solidityType;

    public function setUp(): void
    {
        parent::setUp();

        $this->solidityType = new Address();
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
