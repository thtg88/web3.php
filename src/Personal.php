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
use RuntimeException;
use Web3\Methods\IMethod;
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
    private ?IMethod $method;

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

    public function listAccounts(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('listAccounts', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new ListAccounts($arguments);

        $this->send($callback);
    }

    public function newAccount(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('newAccount', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new NewAccount($arguments);

        $this->send($callback);
    }

    public function unlockAccount(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('unlockAccount', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new UnlockAccount($arguments);

        $this->send($callback);
    }

    public function lockAccount(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('lockAccount', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new LockAccount($arguments);

        $this->send($callback);
    }

    public function sendTransaction(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('sendTransaction', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new SendTransaction($arguments);

        $this->send($callback);
    }

    public function send(callable $callback): void
    {
        if ($this->method === null) {
            throw new RuntimeException('Please set a method.');
        }

        $this->provider->send($this->method, $callback);

        $this->method = null;
    }

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments): void
    {
        $methodClass = sprintf("\Web3\Methods\Personal\%s", ucfirst($name));
        $method = new $methodClass($arguments);

        $this->provider->send($method, null);
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
