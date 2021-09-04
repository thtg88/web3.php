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

    public function clientVersion(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new ClientVersion($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new ClientVersion($arguments), $callback);
    }

    public function sha3(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Sha3($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Sha3($arguments), $callback);
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

    /**
     * @param bool $status
     */
    public function batch($status): void
    {
        $status = is_bool($status);

        $this->provider->batch($status);
    }
}
