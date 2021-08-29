<?php

namespace Web3\Tests\Unit;

use InvalidArgumentException;
use phpseclib\Math\BigInteger as BigNumber;
use RuntimeException;
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

        $net->version(function ($err, $version) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_string($version));
        });
    }

    /** @test */
    public function peer_count(): void
    {
        $net = $this->net;

        $net->peerCount(function ($err, $count) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue($count instanceof BigNumber);
        });
    }

    /** @test */
    public function listening(): void
    {
        $net = $this->net;

        $net->listening(function ($err, $net) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue(is_bool($net));
        });
    }

    /** @test */
    public function unallowed_method(): void
    {
        $this->expectException(RuntimeException::class);

        $net = $this->net;

        $net->hello(function ($err, $hello) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(true);
        });
    }

    /** @test */
    public function wrong_callback(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $net = $this->net;

        $net->version();
    }
}
