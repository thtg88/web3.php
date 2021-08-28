<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use phpseclib\Math\BigInteger as BigNumber;

class EthBatchTest extends TestCase
{
    /**
     * eth
     *
     * @var \Web3\Eth
     */
    protected $eth;

    public function setUp(): void
    {
        parent::setUp();

        $this->eth = $this->web3->eth;
    }

    /**
     * testBatch
     *
     * @return void
     */
    public function testBatch()
    {
        $eth = $this->eth;

        $eth->batch(true);
        $eth->protocolVersion();
        $eth->syncing();

        $eth->provider->execute(function ($err, $data) {
            if ($err !== null) {
                return $this->fail('Got error!');
            }
            $this->assertTrue($data[0] instanceof BigNumber);
            $this->assertTrue($data[1] !== null);
        });
    }
}
