<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Validators\BooleanValidator;

class BooleanValidatorTest extends TestCase
{
    protected BooleanValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = new BooleanValidator();
    }

    /** @test */
    public function validate(): void
    {
        $validator = $this->validator;

        $this->assertEquals(false, $validator->validate('0XCA35B7D915458EF540ADE6068DFE2F44E8FA733C'));
        $this->assertEquals(false, $validator->validate(0xCA35B7D915458EF540ADE6068DFE2F44E8FA733C));
        $this->assertEquals(true, $validator->validate(true));
    }
}
