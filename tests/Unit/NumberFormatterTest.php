<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\NumberFormatter;

class NumberFormatterTest extends TestCase
{
    protected $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new NumberFormatter();
    }

    /** @test */
    public function format(): void
    {
        $formatter = $this->formatter;

        $number= $formatter->format('123');
        $this->assertEquals($number, 123);
    }
}
