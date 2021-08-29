<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\IntegerFormatter;

class IntegerFormatterTest extends TestCase
{
    protected IntegerFormatter $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new IntegerFormatter();
    }

    /** @test */
    public function format(): void
    {
        $formatter = $this->formatter;

        $hex = $formatter->format('1');
        $this->assertEquals($hex, implode('', array_fill(0, 63, '0')) . '1');

        $hex = $formatter->format('-1');
        $this->assertEquals($hex, implode('', array_fill(0, 64, 'f')));

        $hex = $formatter->format('ae');
        $this->assertEquals($hex, implode('', array_fill(0, 62, '0')) . 'ae');

        $hex = $formatter->format('1', 20);
        $this->assertEquals($hex, implode('', array_fill(0, 19, '0')) . '1');

        $hex = $formatter->format(48);
        $this->assertEquals($hex, implode('', array_fill(0, 62, '0')) . '30');

        $hex = $formatter->format('48');
        $this->assertEquals($hex, implode('', array_fill(0, 62, '0')) . '30');
    }
}
