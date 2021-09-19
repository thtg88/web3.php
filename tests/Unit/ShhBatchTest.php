<?php

namespace Web3\Tests\Unit;

use RuntimeException;
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

        $shh->batch(true);
        $shh->version();
        $shh->version();

        [$err, $data] = $shh->provider->execute();

        if ($err !== null) {
            $this->fail('Got error!');
        }

        $this->assertTrue(is_string($data[0]));
        $this->assertTrue(is_string($data[1]));
    }

    /** @test */
    // public function wrong_param(): void
    // {
    //     $this->expectException(RuntimeException::class);

    //     $shh = $this->shh;

    //     $shh->batch(true);
    //     $shh->version();
    //     $shh->hasIdentity('0');

    //     [$err, $data] = $shh->provider->execute();

    //     if ($err !== null) {
    //         $this->fail('Got error!');
    //     }

    //     $this->assertTrue(is_string($data[0]));
    //     $this->assertFalse($data[1]);
    // }
}
