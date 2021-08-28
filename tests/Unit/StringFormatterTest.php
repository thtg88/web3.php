<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\StringFormatter;

class StringFormatterTest extends TestCase
{
    /**
     * formatter
     *
     * @var \Web3\Formatters\StringFormatter
     */
    protected $formatter;

    public function setUp(): void
    {
        parent::setUp();
        $this->formatter = new StringFormatter();
    }

    /**
     * testFormat
     *
     * @return void
     */
    public function testFormat()
    {
        $formatter = $this->formatter;

        $str = $formatter->format(123456);
        $this->assertEquals($str, '123456');
    }
}
