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
use Web3\Methods\Web3\ClientVersion;
use Web3\Methods\Web3\Sha3;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Web3
{
    protected Provider $provider;
    protected Eth $eth;
    protected Net $net;
    protected Personal $personal;
    protected Shh $shh;
    protected Utils $utils;

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

    public function clientVersion(): array|null|self
    {
        $result = $this->provider->send(new ClientVersion());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function sha3(string $data): array|null|self
    {
        $result = $this->provider->send(new Sha3([$data]));

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

    public function getEth(): Eth
    {
        return $this->eth ??= new Eth($this->provider);
    }

    public function getNet(): Net
    {
        return $this->net ??= new Net($this->provider);
    }

    public function getPersonal(): Personal
    {
        return $this->personal ??= new Personal($this->provider);
    }

    public function getShh(): Shh
    {
        return $this->shh ??= new Shh($this->provider);
    }

    public function getUtils(): Utils
    {
        return $this->utils ??= new Utils();
    }

    public function batch(bool $status = true): self
    {
        $this->provider->batch($status);

        return $this;
    }
}
