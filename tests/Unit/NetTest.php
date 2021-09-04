<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Tests\TestCase;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\RequestManager;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Net;

class NetTest extends TestCase
{
    protected Net $net;

    public function setUp(): void
    {
        parent::setUp();

        $this->net = $this->web3->net;
    }

    /** @test */
    public function instance(): void
    {
        $net = new Net($this->testHost);

        $this->assertTrue($net->provider instanceof HttpProvider);
        $this->assertTrue($net->provider->requestManager instanceof RequestManager);
    }

    /** @test */
    public function set_provider(): void
    {
        $net = $this->net;
        $requestManager = new HttpRequestManager('http://localhost:8545');
        $net->provider = new HttpProvider($requestManager);

        $this->assertEquals($net->provider->requestManager->host, 'http://localhost:8545');

        $net->provider = null;

        $this->assertEquals($net->provider->requestManager->host, 'http://localhost:8545');
    }
}
