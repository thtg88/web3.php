<?php

namespace Web3\Tests\Unit;

use phpseclib\Math\BigInteger as BigNumber;
use Web3\Net;
use Web3\Tests\TestCase;

class NetBatchTest extends TestCase
{
    protected Net $net;

    public function setUp(): void
    {
        parent::setUp();

        $this->net = $this->web3->net;
    }

    /** @test */
    public function batch(): void
    {
        $net = $this->net;

        [$errors, $data] = $net->batch()
            ->version()
            ->listening()
            ->peerCount()
            ->execute();

        if ($errors !== null) {
            $this->fail('Got error!');
        }

        $this->assertTrue(is_string($data[0]));
        $this->assertTrue(is_bool($data[1]));
        $this->assertTrue($data[2] instanceof BigNumber);
    }
}
