<?php

namespace Web3\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Web3\Web3;

class TestCase extends BaseTestCase
{
    protected Web3 $web3;
    protected string $testRinkebyHost = 'https://rinkeby.infura.io/vuethexplore';
    protected string $testHost = 'http://127.0.0.1:8545';

    public function setUp(): void
    {
        $this->web3 = new Web3($this->testHost);
    }
}
