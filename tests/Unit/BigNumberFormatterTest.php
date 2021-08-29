<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\BigNumberFormatter;

class BigNumberFormatterTest extends TestCase
{
    protected BigNumberFormatter $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new BigNumberFormatter();
    }

    /** @test */
    public function testFormat(): void
    {
        $formatter = $this->formatter;

        $bigNumber = $formatter->format(1);

        $this->assertEquals($bigNumber, '1');
    }
}
