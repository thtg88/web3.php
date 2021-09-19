<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Tests\TestCase;

class Web3ApiTest extends TestCase
{
    /**
     * 'hello world'
     * you can check by call pack('H*', $hex)
     */
    protected string $testHex = '0x68656c6c6f20776f726c64';

    protected string $testHash = '0x47173285a8d7341e5e972fc677286384f802f8ef42a5ec5f03bbfa254cb01fad';

    /** @test */
    public function client_version(): void
    {
        $web3 = $this->web3;

        [$err, $version] = $web3->clientVersion();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($version));
    }

    /** @test */
    public function sha3_hex(): void
    {
        $web3 = $this->web3;

        [$err, $hash] = $web3->sha3($this->testHex);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertEquals($hash, $this->testHash);
    }

    /** @test */
    public function sha3_hello_world(): void
    {
        $web3 = $this->web3;

        [$err, $hash] = $web3->sha3('hello world');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertEquals($hash, $this->testHash);
    }

    /**
     * We transform data and throw it
     */
    public function wrong_param(): void
    {
        $this->expectException(RuntimeException::class);

        $web3 = $this->web3;

        [$err, $hash] = $web3->sha3($web3);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(true);
    }
}
