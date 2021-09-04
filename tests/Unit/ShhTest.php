<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\RequestManager;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Shh;

class ShhTest extends TestCase
{
    protected Shh $shh;

    public function setUp(): void
    {
        parent::setUp();

        $this->shh = $this->web3->shh;
    }

    /** @test */
    public function instance(): void
    {
        $shh = new Shh($this->testHost);

        $this->assertTrue($shh->provider instanceof HttpProvider);
        $this->assertTrue($shh->provider->requestManager instanceof RequestManager);
    }

    /** @test */
    public function set_provider(): void
    {
        $shh = $this->shh;
        $requestManager = new HttpRequestManager('http://localhost:8545');
        $shh->provider = new HttpProvider($requestManager);

        $this->assertEquals($shh->provider->requestManager->host, 'http://localhost:8545');

        $shh->provider = null;

        $this->assertEquals($shh->provider->requestManager->host, 'http://localhost:8545');
    }
}
