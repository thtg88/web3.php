<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Tests\TestCase;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\RequestManager;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Personal;

class PersonalTest extends TestCase
{
    protected Personal $personal;

    public function setUp(): void
    {
        parent::setUp();

        $this->personal = $this->web3->personal;
    }

    /** @test */
    public function instance(): void
    {
        $personal = new Personal($this->testHost);

        $this->assertTrue($personal->provider instanceof HttpProvider);
        $this->assertTrue($personal->provider->requestManager instanceof RequestManager);
    }

    /** @test */
    public function set_provider(): void
    {
        $personal = $this->personal;
        $requestManager = new HttpRequestManager('http://localhost:8545');
        $personal->provider = new HttpProvider($requestManager);

        $this->assertEquals($personal->provider->requestManager->host, 'http://localhost:8545');

        $personal->provider = null;

        $this->assertEquals($personal->provider->requestManager->host, 'http://localhost:8545');
    }

    /** @test */
    public function call_throw_runtime_exception(): void
    {
        $this->expectException(RuntimeException::class);

        $personal = new Personal(null);
        $personal->newAccount('');
    }
}
