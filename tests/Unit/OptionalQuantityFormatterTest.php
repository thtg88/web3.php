<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\OptionalQuantityFormatter;

class OptionalQuantityFormatterTest extends TestCase
{
    protected OptionalQuantityFormatter $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new OptionalQuantityFormatter();
    }

    /** @test */
    public function format(): void
    {
        $formatter = $this->formatter;

        $this->assertEquals('0x927c0', $formatter->format(0x0927c0));
        $this->assertEquals('0x927c0', $formatter->format('0x0927c0'));
        $this->assertEquals('0x927c0', $formatter->format('0x927c0'));
        $this->assertEquals('0x927c0', $formatter->format('600000'));
        $this->assertEquals('0x927c0', $formatter->format(600000));

        $this->assertEquals('0xea60', $formatter->format('0x0ea60'));
        $this->assertEquals('0xea60', $formatter->format('0xea60'));
        $this->assertEquals('0xea60', $formatter->format(0x0ea60));
        $this->assertEquals('0xea60', $formatter->format('60000'));
        $this->assertEquals('0xea60', $formatter->format(60000));

        $this->assertEquals('0x0', $formatter->format(0x00));
        $this->assertEquals('0x0', $formatter->format('0x00'));
        $this->assertEquals('0x0', $formatter->format('0x0'));
        $this->assertEquals('0x0', $formatter->format('0'));
        $this->assertEquals('0x0', $formatter->format(0));

        $this->assertEquals('latest', $formatter->format('latest'));
        $this->assertEquals('earliest', $formatter->format('earliest'));
        $this->assertEquals('pending', $formatter->format('pending'));
    }
}
