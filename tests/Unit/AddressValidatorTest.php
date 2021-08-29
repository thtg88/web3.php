<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Validators\AddressValidator;

class AddressValidatorTest extends TestCase
{
    protected AddressValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = new AddressValidator();
    }

    /** @test */
    public function validate(): void
    {
        $validator = $this->validator;

        $this->assertEquals(false, $validator->validate('0Xca35b7d915458ef540ade6068dfe2f44e8fa733c'));
        $this->assertEquals(false, $validator->validate('0XCA35B7D915458EF540ADE6068DFE2F44E8FA733C'));
        $this->assertEquals(false, $validator->validate('0xcA35b7D915458eF540ade6068Dfe2f44e8fA733ccA35b7D915458eF540ade6068Dfe2f44e8fA733c'));
        $this->assertEquals(false, $validator->validate('CA35B7D915458EF540ADE6068DFE2F44E8FA733C'));
        $this->assertEquals(false, $validator->validate('1234'));
        $this->assertEquals(false, $validator->validate('abcd'));
        $this->assertEquals(false, $validator->validate(0xCA35B7D915458EF540ADE6068DFE2F44E8FA733C));
        $this->assertEquals(true, $validator->validate('0xCA35B7D915458EF540ADE6068DFE2F44E8FA733C'));
        $this->assertEquals(true, $validator->validate('0xca35b7d915458ef540ade6068dfe2f44e8fa733c'));
        $this->assertEquals(true, $validator->validate('0xcA35b7D915458eF540ade6068Dfe2f44e8fA733c'));
    }
}
