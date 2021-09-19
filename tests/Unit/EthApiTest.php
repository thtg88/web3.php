<?php

namespace Web3\Tests\Unit;

use phpseclib\Math\BigInteger as BigNumber;
use Web3\Eth;
use Web3\Tests\TestCase;

class EthApiTest extends TestCase
{
    protected Eth $eth;

    public function setUp(): void
    {
        parent::setUp();

        $this->eth = $this->web3->eth;
    }

    /** @test */
    public function protocol_version(): void
    {
        $eth = $this->eth;

        [$err, $version] = $eth->protocolVersion();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($version instanceof BigNumber);
    }

    /** @test */
    public function syncing(): void
    {
        $eth = $this->eth;

        [$err, $syncing] = $eth->syncing();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        // due to the result might be object or bool, only test is null
        $this->assertTrue($syncing !== null);
    }

    /** @test */
    public function coinbase(): void
    {
        $eth = $this->eth;

        [$err, $coinbase_expected] = $eth->coinbase();
        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        [$err, $coinbase_actual] = $this->eth->coinbase();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertEquals($coinbase_expected, $coinbase_actual);
    }

    /** @test */
    public function mining(): void
    {
        $eth = $this->eth;

        [$err, $mining] = $eth->mining();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($mining);
    }

    /** @test */
    public function hashrate(): void
    {
        $eth = $this->eth;

        [$err, $hashrate] = $eth->hashrate();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertEquals($hashrate->toString(), '0');
    }

    /** @test */
    public function gas_price(): void
    {
        $eth = $this->eth;

        [$err, $gasPrice] = $eth->gasPrice();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_numeric($gasPrice->toString()));
    }

    /** @test */
    public function accounts(): void
    {
        $eth = $this->eth;

        [$err, $accounts] = $eth->accounts();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_array($accounts));
    }

    /** @test */
    public function block_number(): void
    {
        $eth = $this->eth;

        [$err, $blockNumber] = $eth->blockNumber();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_numeric($blockNumber->toString()));
    }

    /** @test */
    public function get_balance(): void
    {
        $eth = $this->eth;

        [$err, $balance] = $eth->getBalance('0x407d73d8a49eeb85d32cf465507dd71d507100c1');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_numeric($balance->toString()));
    }

    /** @test  */
    public function get_storage_at(): void
    {
        $eth = $this->eth;

        [$err, $storage] = $eth->getStorageAt('0x561a2aa10f9a8589c93665554c871106342f70af', '0x0');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($storage));
    }

    /** @test */
    public function get_transaction_count(): void
    {
        $eth = $this->eth;

        [$err, $transactionCount] = $eth->getTransactionCount('0x561a2aa10f9a8589c93665554c871106342f70af');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_numeric($transactionCount->toString()));
    }

    /** @test */
    public function get_block_transaction_count_by_hash(): void
    {
        $eth = $this->eth;

        [$err, $transactionCount] = $eth->getBlockTransactionCountByHash(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238'
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_numeric($transactionCount->toString()));
    }

    /** @test */
    public function get_block_transaction_count_by_number(): void
    {
        $eth = $this->eth;

        [$err, $transactionCount] = $eth->getBlockTransactionCountByNumber('0x0');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_numeric($transactionCount->toString()));
    }

    /** @test */
    public function get_uncle_count_by_block_hash(): void
    {
        $eth = $this->eth;

        [$err, $uncleCount] = $eth->getUncleCountByBlockHash(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238'
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_numeric($uncleCount->toString()));
    }

    /** @test */
    public function get_uncle_count_by_block_number(): void
    {
        $eth = $this->eth;

        [$err, $uncleCount] = $eth->getUncleCountByBlockNumber('0x0');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_numeric($uncleCount->toString()));
    }

    /** @test */
    public function get_code(): void
    {
        $eth = $this->eth;

        [$err, $code] = $eth->getCode('0x407d73d8a49eeb85d32cf465507dd71d507100c1');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($code));
    }

    /** @test */
    public function sign(): void
    {
        $eth = $this->eth;

        [$err, $sign] = $eth->sign('0x407d73d8a49eeb85d32cf465507dd71d507100c1', '0xdeadbeaf');

        // infura banned us to sign message
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($sign));
    }

    /** @test */
    public function send_transaction(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->sendTransaction([
            'from' => '0xb60e8dd61c5d32be8058bb8eb970870f07233155',
            'to' => '0xd46e8dd67c5d32be8058bb8eb970870f07244567',
            'gas' => '0x76c0',
            'gasPrice' => '0x9184e72a000',
            'value' => '0x9184e72a',
            'data' => '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675',
        ]);

        // infura banned us to send transaction
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($transaction));
    }

    /** @test */
    public function send_raw_transaction(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->sendRawTransaction(
            '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675'
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($transaction));
    }

    /** @test */
    public function call(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->call([
            // 'from' => "0xb60e8dd61c5d32be8058bb8eb970870f07233155",
            'to' => '0xd46e8dd67c5d32be8058bb8eb970870f07244567',
            'gas' => '0x76c0',
            'gasPrice' => '0x9184e72a000',
            'value' => '0x9184e72a',
            'data' => '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675',
        ]);

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($transaction));
    }

    /** @test */
    public function estimate_gas(): void
    {
        $eth = $this->eth;

        [$err, $gas] = $eth->estimateGas([
            'from' => '0xb60e8dd61c5d32be8058bb8eb970870f07233155',
            'to' => '0xd46e8dd67c5d32be8058bb8eb970870f07244567',
            'gas' => '0x76c0',
            'gasPrice' => '0x9184e72a000',
            'value' => '0x9184e72a',
            'data' => '0xd46e8dd67c5d32be8d46e8dd67c5d32be8058bb8eb970870f072445675058bb8eb970870f072445675',
        ]);

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_numeric($gas->toString()));
    }

    /** @test */
    public function get_block_by_hash(): void
    {
        $eth = $this->eth;

        [$err, $block] = $eth->getBlockByHash(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238',
            false,
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue($block !== null);
    }

    /** @test */
    public function get_block_by_number(): void
    {
        $eth = $this->eth;

        [$err, $block] = $eth->getBlockByNumber('latest', false);

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        // weird behavior, see https://github.com/web3p/web3.php/issues/16
        $this->assertTrue($block !== null);
    }

    /** @test */
    public function get_transaction_by_hash(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->getTransactionByHash(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238'
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue($transaction == null);
    }

    /** @test */
    public function get_transaction_by_block_hash_and_index(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->getTransactionByBlockHashAndIndex(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238',
            '0x0',
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue($transaction == null);
    }

    /** @test */
    public function get_transaction_by_block_number_and_index(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->getTransactionByBlockNumberAndIndex('0xe8', '0x0');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue($transaction !== null);
    }

    /** @test */
    public function get_transaction_receipt(): void
    {
        $eth = $this->eth;

        [$err, $transaction] = $eth->getTransactionReceipt(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238'
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }
        $this->assertTrue($transaction == null);
    }

    /** @test */
    public function get_uncle_by_block_hash_and_index(): void
    {
        $eth = $this->eth;

        [$err, $uncle] = $eth->getUncleByBlockHashAndIndex(
            '0xb903239f8543d04b5dc1ba6579132b143087c68db1b2168786408fcbce568238',
            '0x0',
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue($uncle !== null);
    }

    /** @test */
    public function get_uncle_by_block_number_and_index(): void
    {
        $eth = $this->eth;

        [$err, $uncle] = $eth->getUncleByBlockNumberAndIndex('0xe8', '0x0');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue($uncle !== null);
    }

    /** @test */
    public function compile_solidity(): void
    {
        $eth = $this->eth;

        [$err, $compiled] = $eth->compileSolidity(
            'contract test { function multiply(uint a) returns(uint d) { return a * 7; } }'
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($compiled));
    }

    /** @test */
    public function compile_lll(): void
    {
        $eth = $this->eth;

        [$err, $compiled] = $eth->compileLLL('(returnlll (suicide (caller)))');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($compiled));
    }

    /** @test */
    public function compile_serpent(): void
    {
        $eth = $this->eth;

        [$err, $compiled] = $eth->compileSerpent('\/* some serpent *\/');

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($compiled));
    }

    /** @test */
    public function new_filter(): void
    {
        $eth = $this->eth;

        [$err, $filter] = $eth->newFilter([
            'fromBlock' => '0x1',
            'toBlock' => '0x2',
            'address' => '0x8888f1f195afa192cfee860698584c030f4c9db1',
            'topics' => [
                '0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b',
                null,
                [
                    '0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b',
                    '0x0000000000000000000000000aff3454fce5edbc8cca8697c15331677e6ebccc',
                ],
            ],
        ]);

        // infura banned us to new filter
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($filter));
    }

    /** @test */
    public function new_block_filter(): void
    {
        $eth = $this->eth;

        [$err, $filter] = $eth->newBlockFilter();

        // infura banned us to new block filter
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($filter));
    }

    /** @test */
    public function new_pending_transaction_filter(): void
    {
        $eth = $this->eth;

        [$err, $filter] = $eth->newPendingTransactionFilter();

        // infura banned us to new pending transaction filter
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_string($filter));
    }

    /** @test */
    public function uninstall_filter(): void
    {
        $eth = $this->eth;

        [$err, $filter] = $eth->uninstallFilter('0x01');

        // infura banned us to uninstall filter
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_bool($filter));
    }

    /** @test */
    public function get_filter_changes(): void
    {
        $eth = $this->eth;

        [$err, $changes] = $eth->getFilterChanges('0x01');

        // infura banned us to get filter changes
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_array($changes));
    }

    /** @test */
    public function get_filter_logs(): void
    {
        $eth = $this->eth;

        [$err, $logs] = $eth->getFilterLogs('0x01');

        // infura banned us to get filter logs
        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_array($logs));
    }

    /** @test */
    public function get_logs(): void
    {
        $eth = $this->eth;

        [$err, $logs] = $eth->getLogs([
            'fromBlock' => '0x1',
            'toBlock' => '0x2',
            'address' => '0x8888f1f195afa192cfee860698584c030f4c9db1',
            'topics' => [
                '0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b',
                null,
                [
                    '0x000000000000000000000000a94f5374fce5edbc8e2a8697c15331677e6ebf0b',
                    '0x0000000000000000000000000aff3454fce5edbc8cca8697c15331677e6ebccc',
                ],
            ],
        ]);

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_array($logs));
    }

    /** @test */
    public function get_work(): void
    {
        $eth = $this->eth;

        [$err, $work] = $eth->getWork();

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_array($work));
    }

    /** @test */
    public function submit_work(): void
    {
        $eth = $this->eth;

        [$err, $work] = $eth->submitWork(
            '0x0000000000000001',
            '0x1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef',
            '0xD1FE5700000000000000000000000000D1FE5700000000000000000000000000',
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_bool($work));
    }

    /** @test */
    public function submit_hashrate(): void
    {
        $eth = $this->eth;

        [$err, $work] = $eth->submitHashrate(
            '0x1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef',
            '0xD1FE5700000000000000000000000000D1FE5700000000000000000000000000',
        );

        if ($err !== null) {
            $this->assertTrue($err !== null);

            return;
        }

        $this->assertTrue(is_bool($work));
    }
}
