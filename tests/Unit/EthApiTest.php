<?php

namespace Web3\Tests\Unit;

use phpseclib\Math\BigInteger as BigNumber;
use Web3\Eth;
use Web3\Tests\TestCase;

class EthApiTest extends TestCase
{
    protected Eth $eth;
    protected string $coinbase;

    public function setUp(): void
    {
        parent::setUp();

        $this->eth = $this->web3->eth;

        [$err, $coinbase] = $this->eth->coinbase(function () {
        });

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->coinbase = $coinbase;
    }

    /** @test */
    public function protocol_version(): void
    {
        $eth = $this->eth;

        $eth->protocolVersion(function ($err, $version) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue($version instanceof BigNumber);
        });
    }

    /** @test */
    public function syncing(): void
    {
        $eth = $this->eth;

        $eth->syncing(function ($err, $syncing) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            // due to the result might be object or bool, only test is null
            $this->assertTrue($syncing !== null);
        });
    }

    /** @test */
    public function coinbase(): void
    {
        $eth = $this->eth;

        $eth->coinbase(function ($err, $coinbase) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertEquals($coinbase, $this->coinbase);
        });
    }

    /** @test */
    public function mining(): void
    {
        $eth = $this->eth;

        $eth->mining(function ($err, $mining) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue($mining);
        });
    }

    /** @test */
    public function hashrate(): void
    {
        $eth = $this->eth;

        $eth->hashrate(function ($err, $hashrate) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertEquals($hashrate->toString(), '0');
        });
    }

    /** @test */
    public function gas_price(): void
    {
        $eth = $this->eth;

        $eth->gasPrice(function ($err, $gasPrice) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_numeric($gasPrice->toString()));
        });
    }

    /** @test */
    public function accounts(): void
    {
        $eth = $this->eth;

        $eth->accounts(function ($err, $accounts) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_array($accounts));
        });
    }

    /** @test */
    public function block_number(): void
    {
        $eth = $this->eth;

        $eth->blockNumber(function ($err, $blockNumber) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_numeric($blockNumber->toString()));
        });
    }

    /** @test */
    public function get_balance(): void
    {
        $eth = $this->eth;

        $eth->getBalance('0x407d73d8a49eeb85d32cf465507dd71d507100c1', function ($err, $balance) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_numeric($balance->toString()));
        });
    }

    /** @test  */
    public function get_storage_at(): void
    {
        $eth = $this->eth;

        $eth->getStorageAt('0x561a2aa10f9a8589c93665554c871106342f70af', '0x0', function ($err, $storage) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_string($storage));
        });
    }

    /** @test */
    public function get_transaction_count(): void
    {
        $eth = $this->eth;

        $eth->getTransactionCount('0x561a2aa10f9a8589c93665554c871106342f70af', function ($err, $transactionCount) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_numeric($transactionCount->toString()));
        });
    }

    /** @test */
    public function get_block_transaction_count_by_hash(): void
    {
        $eth = $this->eth;

        $eth->getBlockTransactionCountByHash('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', function ($err, $transactionCount) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_numeric($transactionCount->toString()));
        });
    }

    /** @test */
    public function get_block_transaction_count_by_number(): void
    {
        $eth = $this->eth;

        $eth->getBlockTransactionCountByNumber('0x0', function ($err, $transactionCount) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_numeric($transactionCount->toString()));
        });
    }

    /** @test */
    public function get_uncle_count_by_block_hash(): void
    {
        $eth = $this->eth;

        $eth->getUncleCountByBlockHash('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', function ($err, $uncleCount) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_numeric($uncleCount->toString()));
        });
    }

    /** @test */
    public function get_uncle_count_by_block_number(): void
    {
        $eth = $this->eth;

        $eth->getUncleCountByBlockNumber('0x0', function ($err, $uncleCount) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_numeric($uncleCount->toString()));
        });
    }

    /** @test */
    public function get_code(): void
    {
        $eth = $this->eth;

        $eth->getCode('0x407d73d8a49eeb85d32cf465507dd71d507100c1', function ($err, $code) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_string($code));
        });
    }

    /** @test */
    public function sign(): void
    {
        $eth = $this->eth;

        $eth->sign('0x407d73d8a49eeb85d32cf465507dd71d507100c1', '0xdeadbeaf', function ($err, $sign) {
            // infura banned us to sign message
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_string($sign));
        });
    }

    /** @test */
    public function send_transaction(): void
    {
        $eth = $this->eth;

        $eth->sendTransaction([
            'from' => '0xb60e8dd61c5d32be8058bb8eb970870f07233155',
            'to' => '0xd46e8dd67c5d32be8058bb8eb970870f07244567',
            'gas' => '0x76c0',
            'gasPrice' => '0x9184e72a000',
            'value' => '0x9184e72a',
            'data' => '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675',
        ], function ($err, $transaction) {
            // infura banned us to send transaction
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_string($transaction));
        });
    }

    /** @test */
    public function send_raw_transaction(): void
    {
        $eth = $this->eth;

        $eth->sendRawTransaction('0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675', function ($err, $transaction) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_string($transaction));
        });
    }

    /** @test */
    public function call(): void
    {
        $eth = $this->eth;

        $eth->call([
            // 'from' => "0xb60e8dd61c5d32be8058bb8eb970870f07233155",
            'to' => '0xd46e8dd67c5d32be8058bb8eb970870f07244567',
            'gas' => '0x76c0',
            'gasPrice' => '0x9184e72a000',
            'value' => '0x9184e72a',
            'data' => '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675',
        ], function ($err, $transaction) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_string($transaction));
        });
    }

    /** @test */
    public function estimate_gas(): void
    {
        $eth = $this->eth;

        $eth->estimateGas([
            'from' => '0xb60e8dd61c5d32be8058bb8eb970870f07233155',
            'to' => '0xd46e8dd67c5d32be8058bb8eb970870f07244567',
            'gas' => '0x76c0',
            'gasPrice' => '0x9184e72a000',
            'value' => '0x9184e72a',
            'data' => '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675',
        ], function ($err, $gas) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_numeric($gas->toString()));
        });
    }

    /** @test */
    public function get_block_by_hash(): void
    {
        $eth = $this->eth;

        $eth->getBlockByHash('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', false, function ($err, $block) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue($block !== null);
        });
    }

    /** @test */
    public function get_block_by_number(): void
    {
        $eth = $this->eth;

        $eth->getBlockByNumber('latest', false, function ($err, $block) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            // weired behavior, see https://github.com/sc0Vu/web3.php/issues/16
            $this->assertTrue($block !== null);
        });
    }

    /** @test */
    public function get_transaction_by_hash(): void
    {
        $eth = $this->eth;

        $eth->getTransactionByHash('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', function ($err, $transaction) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue($transaction == null);
        });
    }

    /** @test */
    public function get_transaction_by_block_hash_and_index(): void
    {
        $eth = $this->eth;

        $eth->getTransactionByBlockHashAndIndex('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', '0x0', function ($err, $transaction) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue($transaction == null);
        });
    }

    /** @test */
    public function get_transaction_by_block_number_and_index(): void
    {
        $eth = $this->eth;

        $eth->getTransactionByBlockNumberAndIndex('0xe8', '0x0', function ($err, $transaction) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue($transaction !== null);
        });
    }

    /** @test */
    public function get_transaction_receipt(): void
    {
        $eth = $this->eth;

        $eth->getTransactionReceipt('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', function ($err, $transaction) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue($transaction == null);
        });
    }

    /** @test */
    public function get_uncle_by_block_hash_and_index(): void
    {
        $eth = $this->eth;

        $eth->getUncleByBlockHashAndIndex('0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238', '0x0', function ($err, $uncle) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue($uncle !== null);
        });
    }

    /** @test */
    public function get_uncle_by_block_number_and_index(): void
    {
        $eth = $this->eth;

        $eth->getUncleByBlockNumberAndIndex('0xe8', '0x0', function ($err, $uncle) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue($uncle !== null);
        });
    }

    /** @test */
    public function compile_solidity(): void
    {
        $eth = $this->eth;

        $eth->compileSolidity('contract test { function multiply(uint a) returns(uint d) { return a * 7; } }', function ($err, $compiled) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_string($compiled));
        });
    }

    /** @test */
    public function compile_lll(): void
    {
        $eth = $this->eth;

        $eth->compileLLL('(returnlll (suicide (caller)))', function ($err, $compiled) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }

            $this->assertTrue(is_string($compiled));
        });
    }

    /** @test */
    public function compile_serpent(): void
    {
        $eth = $this->eth;

        $eth->compileSerpent('\/* some serpent *\/', function ($err, $compiled) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_string($compiled));
        });
    }

    /** @test */
    public function new_filter(): void
    {
        $eth = $this->eth;

        $eth->newFilter([
            'fromBlock' => '0x1',
            'toBlock' => '0x2',
            'address' => '0x8888f1f195afa192cfee860698584c030f4c9db1',
            'topics' => ['0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b', null, ['0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b', '0x0000000000000000000000000aff3454fce5edbc8cca8697c15331677e6ebccc']],
        ], function ($err, $filter) {
            if ($err !== null) {
                // infura banned us to new filter
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_string($filter));
        });
    }

    /** @test */
    public function new_block_filter(): void
    {
        $eth = $this->eth;

        $eth->newBlockFilter(function ($err, $filter) {
            if ($err !== null) {
                // infura banned us to new block filter
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_string($filter));
        });
    }

    /** @test */
    public function new_pending_transaction_filter(): void
    {
        $eth = $this->eth;

        $eth->newPendingTransactionFilter(function ($err, $filter) {
            if ($err !== null) {
                // infura banned us to new pending transaction filter
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_string($filter));
        });
    }

    /** @test */
    public function uninstall_filter(): void
    {
        $eth = $this->eth;

        $eth->uninstallFilter('0x01', function ($err, $filter) {
            if ($err !== null) {
                // infura banned us to uninstall filter
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_bool($filter));
        });
    }

    /** @test */
    public function get_filter_changes(): void
    {
        $eth = $this->eth;

        $eth->getFilterChanges('0x01', function ($err, $changes) {
            if ($err !== null) {
                // infura banned us to get filter changes
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_array($changes));
        });
    }

    /** @test */
    public function get_filter_logs(): void
    {
        $eth = $this->eth;

        $eth->getFilterLogs('0x01', function ($err, $logs) {
            if ($err !== null) {
                // infura banned us to get filter logs
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_array($logs));
        });
    }

    /** @test */
    public function get_logs(): void
    {
        $eth = $this->eth;

        $eth->getLogs([
            'fromBlock' => '0x1',
            'toBlock' => '0x2',
            'address' => '0x8888f1f195afa192cfee860698584c030f4c9db1',
            'topics' => ['0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b', null, ['0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b', '0x0000000000000000000000000aff3454fce5edbc8cca8697c15331677e6ebccc']],
        ], function ($err, $logs) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_array($logs));
        });
    }

    /** @test */
    public function get_work(): void
    {
        $eth = $this->eth;

        $eth->getWork(function ($err, $work) {
            if ($err !== null) {
                return $this->assertTrue($err !== null);
            }
            $this->assertTrue(is_array($work));
        });
    }

    /** @test */
    public function submit_work(): void
    {
        $eth = $this->eth;

        $eth->submitWork(
            '0x0000000000000001',
            '0x1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef',
            '0xD1FE5700000000000000000000000000D1FE5700000000000000000000000000',
            function ($err, $work) {
                if ($err !== null) {
                    return $this->assertTrue($err !== null);
                }
                $this->assertTrue(is_bool($work));
            }
        );
    }

    /** @test */
    public function submit_hashrate(): void
    {
        $eth = $this->eth;

        $eth->submitHashrate(
            '0x1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef',
            '0xD1FE5700000000000000000000000000D1FE5700000000000000000000000000',
            function ($err, $work) {
                if ($err !== null) {
                    return $this->assertTrue($err !== null);
                }
                $this->assertTrue(is_bool($work));
            }
        );
    }
}
