<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Validators\StringValidator;

class StringValidatorTest extends TestCase
{
    protected StringValidator $validator;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new StringValidator();
    }

    /** @test */
    public function validate(): void
    {
        $validator = $this->validator;

        $this->assertEquals(true, $validator->validate('0Xca35b7d915458ef540ade6068dfe2f44e8fa733c'));
        $this->assertEquals(false, $validator->validate(1234));
        $this->assertEquals(false, $validator->validate(0xCA35B7D915458EF540ADE6068DFE2F44E8FA733C));
    }
}
