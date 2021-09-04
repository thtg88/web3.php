<?php

namespace Web3\Tests\Unit;

use InvalidArgumentException;
use RuntimeException;
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

        $this->web3->eth->coinbase(function ($err, $coinbase) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->coinbase = $coinbase;
        });
    }

    /** @test */
    public function list_accounts(): void
    {
        $personal = $this->personal;

        $personal->listAccounts(function ($err, $accounts) {
            // infura banned us to use list accounts
            if ($err !== null) {
                return $this->assertTrue($err->getCode() === 405);
            }

            $this->assertTrue(is_array($accounts));
        });
    }

    /** @test */
    public function new_account(): void
    {
        $personal = $this->personal;

        $personal->newAccount('123456', function ($err, $account) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_string($account));
        });
    }

    /** @test */
    public function unlock_account(): void
    {
        $personal = $this->personal;

        // create account
        $personal->newAccount('123456', function ($err, $account) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->newAccount = $account;
            $this->assertTrue(is_string($account));
        });

        $personal->unlockAccount($this->newAccount, '123456', function ($err, $unlocked) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue($unlocked);
        });
    }

    /** @test */
    public function unlock_account_with_duration(): void
    {
        $personal = $this->personal;

        // create account
        $personal->newAccount('123456', function ($err, $account) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->newAccount = $account;
            $this->assertTrue(is_string($account));
        });

        $personal->unlockAccount($this->newAccount, '123456', 100, function ($err, $unlocked) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue($unlocked);
        });
    }

    /** @test */
    public function lock_account(): void
    {
        $personal = $this->personal;

        // create account
        $personal->newAccount('123456', function ($err, $account) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->newAccount = $account;
            $this->assertTrue(is_string($account));
        });

        $personal->unlockAccount($this->newAccount, '123456', function ($err, $unlocked) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue($unlocked);
        });

        $personal->lockAccount($this->newAccount, function ($err, $locked) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue($locked);
        });
    }

    /** @test */
    public function send_transaction(): void
    {
        $personal = $this->personal;

        // create account
        $personal->newAccount('123456', function ($err, $account) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->newAccount = $account;
            $this->assertTrue(is_string($account));
        });

        $this->web3->eth->sendTransaction([
            'from' => $this->coinbase,
            'to' => $this->newAccount,
            'value' => '0xfffffffff',
        ], function ($err, $transaction) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_string($transaction));
            $this->assertTrue(mb_strlen($transaction) === 66);
        });

        $personal->sendTransaction([
            'from' => $this->newAccount,
            'to' => $this->coinbase,
            'value' => '0x01',
        ], '123456', function ($err, $transaction) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_string($transaction));
            $this->assertTrue(mb_strlen($transaction) === 66);
        });
    }

    /** @test */
    public function unallowed_method(): void
    {
        $this->expectException(RuntimeException::class);

        $personal = $this->personal;

        $personal->hello(function ($err, $hello) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue(true);
        });
    }

    /** @test */
    public function wrong_param(): void
    {
        $this->expectException(RuntimeException::class);

        $personal = $this->personal;

        $personal->newAccount($personal, function ($err, $account) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue(is_string($account));
        });
    }
}
