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
use Web3\Methods\Net\Listening;
use Web3\Methods\Net\PeerCount;
use Web3\Methods\Net\Version;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Net
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

    public function listening(): array|null|self
    {
        $result = $this->provider->send(new Listening());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function peerCount(): array|null|self
    {
        $result = $this->provider->send(new PeerCount());

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
