<?php

namespace Web3\Tests\Unit;

use Web3\RequestManagers\HttpRequestManager;
use Web3\Tests\TestCase;

class HttpRequestManagerTest extends TestCase
{
    /** @test */
    public function set_host(): void
    {
        $requestManager = new HttpRequestManager('http://localhost:8545', 0.1);
        $this->assertEquals($requestManager->host, 'http://localhost:8545');
        $this->assertEquals($requestManager->timeout, 0.1);
    }
}
