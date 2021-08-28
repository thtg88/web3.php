<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\NumberFormatter;

class NumberFormatterTest extends TestCase
{
    /**
     * formatter
     *
     * @var \Web3\Formatters\NumberFormatter
     */
    protected $formatter;

    public function setUp(): void
    {
        parent::setUp();
        $this->formatter = new NumberFormatter();
    }

    /**
     * testFormat
     *
     * @return void
     */
    public function testFormat()
    {
        $formatter = $this->formatter;

        $number= $formatter->format('123');
        $this->assertEquals($number, 123);
    }
}
