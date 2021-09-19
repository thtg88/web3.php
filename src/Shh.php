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
use Web3\Methods\Shh\GetFilterChanges;
use Web3\Methods\Shh\GetMessages;
use Web3\Methods\Shh\HasIdentity;
use Web3\Methods\Shh\NewFilter;
use Web3\Methods\Shh\NewIdentity;
use Web3\Methods\Shh\Post;
use Web3\Methods\Shh\UninstallFilter;
use Web3\Methods\Shh\Version;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Shh
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

    public function addToGroup(...$arguments): void
    {
        throw new RuntimeException('Method not implemented.');
    }

    public function getFilterChanges(...$arguments): array|null|self
    {
        $result = $this->provider->send(new GetFilterChanges($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getMessages(...$arguments): array|null|self
    {
        $result = $this->provider->send(new GetMessages($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function hasIdentity(...$arguments): array|null|self
    {
        $result = $this->provider->send(new HasIdentity($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newFilter(...$arguments): array|null|self
    {
        $result = $this->provider->send(new NewFilter($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newGroup(...$arguments): void
    {
        throw new RuntimeException('Method not implemented.');
    }

    public function newIdentity(...$arguments): array|null|self
    {
        $result = $this->provider->send(new NewIdentity($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function post(...$arguments): array|null|self
    {
        $result = $this->provider->send(new Post($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function uninstallFilter(...$arguments): array|null|self
    {
        $result = $this->provider->send(new UninstallFilter($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function version(...$arguments): array|null|self
    {
        $result = $this->provider->send(new Version($arguments));

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
