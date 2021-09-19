<?php

namespace Web3\Tests\Unit;

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

        [$err, $data] = $personal->provider->execute();

        if ($err !== null) {
            $this->assertTrue($err !== null);
        }

        $this->assertTrue(is_array($data[0]));
        $this->assertTrue(is_string($data[1]));
    }
}
