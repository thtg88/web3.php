<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\RequestManagers\RequestManager;

class RequestManagerTest extends TestCase
{
    /**
     * testSetHost
     *
     * @return void
     */
    public function testSetHost()
    {
        $requestManager = new RequestManager('http://localhost:8545', 0.1);
        $this->assertEquals($requestManager->host, 'http://localhost:8545');
        $this->assertEquals($requestManager->timeout, 0.1);

        $requestManager->host = $this->testRinkebyHost;
        $requestManager->timeout = 1;
        $this->assertEquals($requestManager->host, 'http://localhost:8545');
        $this->assertEquals($requestManager->timeout, 0.1);
    }
}
