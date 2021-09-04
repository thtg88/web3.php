<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Tests\TestCase;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\RequestManager;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Eth;

class EthTest extends TestCase
{
    protected Eth $eth;

    public function setUp(): void
    {
        parent::setUp();

        $this->eth = $this->web3->eth;
    }

    /** @test */
    public function instance(): void
    {
        $eth = new Eth($this->testHost);

        $this->assertTrue($eth->provider instanceof HttpProvider);
        $this->assertTrue($eth->provider->requestManager instanceof RequestManager);
    }

    /** @test */
    public function set_provider(): void
    {
        $eth = $this->eth;
        $requestManager = new HttpRequestManager('http://localhost:8545');
        $eth->provider = new HttpProvider($requestManager);

        $this->assertEquals($eth->provider->requestManager->host, 'http://localhost:8545');

        $eth->provider = null;

        $this->assertEquals($eth->provider->requestManager->host, 'http://localhost:8545');
    }
}
