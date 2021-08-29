<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Formatters\BooleanFormatter;

class BooleanFormatterTest extends TestCase
{
    protected BooleanFormatter $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new BooleanFormatter();
    }

    /** @test */
    public function format(): void
    {
        $formatter = $this->formatter;

        $boolean = $formatter->format(true);
        $this->assertEquals($boolean, true);

        $boolean = $formatter->format(1);
        $this->assertEquals($boolean, true);

        $boolean = $formatter->format(false);
        $this->assertEquals($boolean, false);

        $boolean = $formatter->format(0);
        $this->assertEquals($boolean, false);
    }
}
