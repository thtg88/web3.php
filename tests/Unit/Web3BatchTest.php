<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;

class Web3BatchTest extends TestCase
{
    /**
     * 'hello world'
     * you can check by call pack('H*', $hex)
     */
    protected string $testHex = '0x68656c6c6f20776f726c64';

    protected string $testHash = '0x47173285a8d7341e5e972fc677286384f802f8ef42a5ec5f03bbfa254cb01fad';

    /** @test */
    public function batch(): void
    {
        $web3 = $this->web3;

        $web3->batch(true);
        $web3->clientVersion();
        $web3->sha3($this->testHex);

        [$err, $data] = $web3->provider->execute();

        if ($err !== null) {
            $this->fail('Got error!');
        }

        $this->assertTrue(is_string($data[0]));
        $this->assertEquals($data[1], $this->testHash);
    }
}
