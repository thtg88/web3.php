<?php

namespace Web3\Tests\Unit;

use phpseclib\Math\BigInteger as BigNumber;
use Web3\Eth;
use Web3\Tests\TestCase;

class EthBatchTest extends TestCase
{
    protected Eth $eth;

    public function setUp(): void
    {
        parent::setUp();

        $this->eth = $this->web3->eth;
    }

    /** @test */
    public function batch(): void
    {
        $eth = $this->eth;

        [$errors, $data] = $eth->batch()
            ->protocolVersion()
            ->syncing()
            ->execute();

        if ($errors !== null) {
            $this->fail('Got error!');
        }

        $this->assertTrue($data[0] instanceof BigNumber);
        $this->assertTrue($data[1] !== null);
    }
}
