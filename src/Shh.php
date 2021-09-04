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

    public function addToGroup(...$arguments): void
    {
        throw new RuntimeException('Method not implemented.');
    }

    public function getFilterChanges(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getFilterChanges', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetFilterChanges(arguments: $arguments);

        $this->send($callback);
    }

    public function getMessages(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getMessages', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetMessages(arguments: $arguments);

        $this->send($callback);
    }

    public function hasIdentity(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('hasIdentity', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new HasIdentity(arguments: $arguments);

        $this->send($callback);
    }

    public function newFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('newFilter', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new NewFilter(arguments: $arguments);

        $this->send($callback);
    }

    public function newGroup(...$arguments): void
    {
        throw new RuntimeException('Method not implemented.');
    }

    public function newIdentity(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('newIdentity', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new NewIdentity(arguments: $arguments);

        $this->send($callback);
    }

    public function post(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('post', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Post(arguments: $arguments);

        $this->send($callback);
    }

    public function uninstallFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('uninstallFilter', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new UninstallFilter(arguments: $arguments);

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

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments): void
    {
        $method_name = 'shh_' . $name;

        if (!array_key_exists($method_name, $this->methods)) {
            // new the method
            $methodClass = sprintf("\Web3\Methods\Shh\%s", ucfirst($name));
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
