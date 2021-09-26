<?php

namespace Web3\Tests\Unit;

use Web3\Shh;
use Web3\Tests\TestCase;

class ShhBatchTest extends TestCase
{
    protected Shh $shh;

    public function setUp(): void
    {
        parent::setUp();

        $this->shh = $this->web3->shh;
    }

    /** @test */
    public function batch(): void
    {
        $shh = $this->shh;

        [$errors, $data] = $shh->batch()
            ->version()
            ->version()
            ->execute();

        if ($errors !== null) {
            $this->fail('Got error!');
        }

        $this->assertTrue(is_string($data[0]));
        $this->assertTrue(is_string($data[1]));
    }
}
