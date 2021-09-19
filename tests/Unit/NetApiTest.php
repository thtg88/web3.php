<?php

namespace Web3\Tests\Unit;

use phpseclib\Math\BigInteger as BigNumber;
use Web3\Net;
use Web3\Tests\TestCase;

class NetApiTest extends TestCase
{
    protected Net $net;

    public function setUp(): void
    {
        parent::setUp();

        $this->net = $this->web3->net;
    }

    /** @test */
    public function version(): void
    {
        $net = $this->net;

        [$err, $version] = $net->version();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($version));
    }

    /** @test */
    public function peer_count(): void
    {
        $net = $this->net;

        [$err, $count] = $net->peerCount();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($count instanceof BigNumber);
    }

    /** @test */
    public function listening(): void
    {
        $net = $this->net;

        [$err, $net] = $net->listening();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_bool($net));
    }
}
