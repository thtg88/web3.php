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

    public function listAccounts(...$arguments): array|null|self
    {
        $result = $this->provider->send(new ListAccounts($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newAccount(...$arguments): array|null|self
    {
        $result = $this->provider->send(new NewAccount($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function unlockAccount(...$arguments): array|null|self
    {
        $result = $this->provider->send(new UnlockAccount($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function lockAccount(...$arguments): array|null|self
    {
        $result = $this->provider->send(new LockAccount($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function sendTransaction(...$arguments): array|null|self
    {
        $result = $this->provider->send(new SendTransaction($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }

        return false;
    }

    /**
     * @param string $name
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$value]);
        }

        return false;
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

    /**
     * @param bool $status
     */
    public function batch($status): void
    {
        $status = is_bool($status);

        $this->provider->batch($status);
    }
}
