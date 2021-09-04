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
use Web3\Methods\Net\Listening;
use Web3\Methods\Net\PeerCount;
use Web3\Methods\Net\Version;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Net
{
    protected Provider $provider;

    private array $methods = [];
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

    public function listening(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('listening', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Listening(arguments: $arguments);

        $this->send($callback);
    }

    public function peerCount(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('peerCount', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new PeerCount(arguments: $arguments);

        $this->send($callback);
    }

    public function version(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('version', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Version(arguments: $arguments);

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
        $method_name = 'net_' . $name;

        if (!array_key_exists($method_name, $this->methods)) {
            // new the method
            $methodClass = sprintf("\Web3\Methods\Net\%s", ucfirst($name));
            $method = new $methodClass($method_name, $arguments);
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
