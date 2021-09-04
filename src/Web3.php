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
    private array $methods = [];
    private IMethod $method;
    private $callback;

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
            $this->__call('clientVersion', $arguments);
        }

        $callback = array_pop($arguments);

        $this->method = new ClientVersion($arguments);

        $this->send($callback);
    }

    public function sha3(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('sha3', $arguments);
        }

        $callback = array_pop($arguments);

        $this->method = new Sha3($arguments);

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
     * Please note: this is only used for batch request.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments): void
    {
        $method_name = 'web3_' . $name;

        if (!array_key_exists($method_name, $this->methods)) {
            // new the method
            $methodClass = sprintf("\Web3\Methods\Web3\%s", ucfirst($name));
            $method = new $methodClass(arguments: $arguments);
            $this->methods[$method_name] = $method;
        } else {
            $method = $this->methods[$method_name];
        }

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

    /**
     * @param \Web3\Providers\Provider $provider
     */
    public function setProvider($provider): self
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
