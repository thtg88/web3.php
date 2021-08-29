<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Validators\TagValidator;

class TagValidatorTest extends TestCase
{
    protected TagValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = new TagValidator();
    }

    /** @test */
    public function validate(): void
    {
        $validator = $this->validator;

        $this->assertEquals(false, $validator->validate(1234));
        $this->assertEquals(false, $validator->validate(0xCA35B7D915458EF540ADE6068DFE2F44E8FA733C));
        $this->assertEquals(false, $validator->validate('hello'));
        $this->assertEquals(true, $validator->validate('latest'));
        $this->assertEquals(true, $validator->validate('earliest'));
        $this->assertEquals(true, $validator->validate('pending'));
    }
}
