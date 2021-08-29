<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Personal;
use Web3\Tests\TestCase;

class PersonalBatchTest extends TestCase
{
    protected Personal $personal;

    public function setUp(): void
    {
        parent::setUp();

        $this->personal = $this->web3->personal;
    }

    /** @test */
    public function batch(): void
    {
        $personal = $this->personal;

        $personal->batch(true);
        $personal->listAccounts();
        $personal->newAccount('123456');

        $personal->provider->execute(function ($err, $data) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_array($data[0]));
            $this->assertTrue(is_string($data[1]));
        });
    }

    /** @test */
    public function wrong_param(): void
    {
        $this->expectException(RuntimeException::class);

        $personal = $this->personal;

        $personal->batch(true);
        $personal->listAccounts();
        $personal->newAccount($personal);

        $personal->provider->execute(function ($err, $data) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue(is_string($data[0]));
            $this->assertEquals($data[1], $this->testHash);
        });
    }
}
