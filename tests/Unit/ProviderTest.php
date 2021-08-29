<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\RequestManagers\RequestManager;
use Web3\Providers\Provider;

class ProviderTest extends TestCase
{
    /** @test */
    public function set_request_manager(): void
    {
        $requestManager = new RequestManager('http://localhost:8545');
        $provider = new Provider($requestManager);

        $this->assertEquals($provider->requestManager->host, 'http://localhost:8545');

        $requestManager = new RequestManager($this->testRinkebyHost);
        $provider->requestManager = $requestManager;

        $this->assertEquals($provider->requestManager->host, 'http://localhost:8545');
    }
}
