<?php

namespace Web3\Tests\Unit;

use Web3\Personal;
use Web3\Tests\TestCase;

class PersonalApiTest extends TestCase
{
    protected Personal $personal;
    protected string $newAccount;

    public function setUp(): void
    {
        parent::setUp();

        $this->personal = $this->web3->personal;

        [$err, $coinbase] = $this->web3->eth->coinbase();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->coinbase = $coinbase;
    }

    /** @test */
    public function list_accounts(): void
    {
        $personal = $this->personal;

        [$err, $accounts] = $personal->listAccounts();

        // infura banned us to use list accounts
        if ($err !== null) {
            $this->assertTrue($err->getCode() === 405);
        }

        $this->assertTrue(is_array($accounts));
    }

    /** @test */
    public function new_account(): void
    {
        $personal = $this->personal;

        [$err, $account] = $personal->newAccount('123456');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($account));
    }

    /** @test */
    public function unlock_account(): void
    {
        $personal = $this->personal;

        // create account
        [$err, $account] = $personal->newAccount('123456');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->newAccount = $account;

        $this->assertTrue(is_string($account));

        [$err, $unlocked] = $personal->unlockAccount($this->newAccount, '123456', 0);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($unlocked);
    }

    /** @test */
    public function unlock_account_with_duration(): void
    {
        $personal = $this->personal;

        // create account
        [$err, $account] = $personal->newAccount('123456');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->newAccount = $account;

        $this->assertTrue(is_string($account));

        [$err, $unlocked] = $personal->unlockAccount($this->newAccount, '123456', 100);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($unlocked);
    }

    /** @test */
    public function lock_account(): void
    {
        $personal = $this->personal;

        [$err, $account] = $personal->newAccount('123456');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->newAccount = $account;

        $this->assertTrue(is_string($account));

        [$err, $unlocked] = $personal->unlockAccount($this->newAccount, '123456', 0);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($unlocked);

        [$err, $locked] = $personal->lockAccount($this->newAccount);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue($locked);
    }

    /** @test */
    public function send_transaction(): void
    {
        $personal = $this->personal;

        [$err, $account] = $personal->newAccount('123456');
        if ($err !== null) {
            $this->fail($err->getMessage());
        }
        $this->newAccount = $account;
        $this->assertTrue(is_string($account));

        [$err, $transaction] = $this->web3->eth->sendTransaction([
            'from' => $this->coinbase,
            'to' => $this->newAccount,
            'value' => '0xfffffffff',
        ]);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($transaction));
        $this->assertTrue(mb_strlen($transaction) === 66);

        [$err, $transaction] = $personal->sendTransaction([
            'from' => $this->newAccount,
            'to' => $this->coinbase,
            'value' => '0x01',
        ], '123456');

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($transaction));
        $this->assertTrue(mb_strlen($transaction) === 66);
    }
}
