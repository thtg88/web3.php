<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3;

use InvalidArgumentException;
use Web3\Methods\Personal\ListAccounts;
use Web3\Methods\Personal\LockAccount;
use Web3\Methods\Personal\NewAccount;
use Web3\Methods\Personal\SendTransaction;
use Web3\Methods\Personal\UnlockAccount;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Personal
{
    protected Provider $provider;

    public function __construct(Provider|string $provider)
    {
        if ($provider instanceof Provider) {
            $this->provider = $provider;

            return;
        }

        // check the uri schema
        if (
            filter_var($provider, FILTER_VALIDATE_URL) !== false &&
            preg_match('/^https?:\/\//', $provider) === 1
        ) {
            $requestManager = new HttpRequestManager($provider);

            $this->provider = new HttpProvider($requestManager);

            return;
        }

        throw new InvalidArgumentException('Please set a valid provider.');
    }

    public function listAccounts(): array|null|self
    {
        $result = $this->provider->send(new ListAccounts());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newAccount(string $passphrase): array|null|self
    {
        $result = $this->provider->send(new NewAccount([$passphrase]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function unlockAccount(string $address, string $passphrase, $duration = 300): array|null|self
    {
        $result = $this->provider->send(new UnlockAccount([
            $address,
            $passphrase,
            $duration,
        ]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function lockAccount(string $address): array|null|self
    {
        $result = $this->provider->send(new LockAccount([$address]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function sendTransaction($transaction, string $passphrase): array|null|self
    {
        $result = $this->provider->send(new SendTransaction([$transaction, $passphrase]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);

        return $this->$method();
    }

    public function __set(string $name, mixed $value)
    {
        $method = 'set' . ucfirst($name);

        return $this->$method($value);
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function setProvider(Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function batch(bool $status = true): self
    {
        $this->provider->batch($status);

        return $this;
    }
}
