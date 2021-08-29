<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\StringFormatter;

class StringFormatterTest extends TestCase
{
    protected StringFormatter $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new StringFormatter();
    }

    /** @test */
    public function format(): void
    {
        $formatter = $this->formatter;

        $str = $formatter->format(123456);
        $this->assertEquals($str, '123456');
    }
}
