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

    public function getFilterChanges(float|int|string $filter_id): array|null|self
    {
        $result = $this->provider->send(new GetFilterChanges([$filter_id]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getMessages(float|int|string $filter_id): array|null|self
    {
        $result = $this->provider->send(new GetMessages([$filter_id]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function hasIdentity(string $identity): array|null|self
    {
        $result = $this->provider->send(new HasIdentity([$identity]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newFilter(array $filter): array|null|self
    {
        $result = $this->provider->send(new NewFilter([$filter]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newGroup(...$arguments): void
    {
        throw new RuntimeException('Method not implemented.');
    }

    public function newIdentity(): array|null|self
    {
        $result = $this->provider->send(new NewIdentity());

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

    public function uninstallFilter(float|int|string $filter_id): array|null|self
    {
        $result = $this->provider->send(new UninstallFilter([$filter_id]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function version(): array|null|self
    {
        $result = $this->provider->send(new Version());

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
