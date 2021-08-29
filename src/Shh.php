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
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Shh
{
    /**
     * provider
     *
     * @var \Web3\Providers\Provider
     */
    protected $provider;

    private array $methods = [];

    private array $allowedMethods = [
        'shh_version',
        'shh_newIdentity',
        'shh_hasIdentity',
        'shh_post',
        'shh_newFilter',
        'shh_uninstallFilter',
        'shh_getFilterChanges',
        'shh_getMessages',
        // doesn't exist: 'shh_newGroup', 'shh_addToGroup'
    ];

    /**
     * @param string|\Web3\Providers\Provider $provider
     */
    public function __construct($provider)
    {
        if (is_string($provider) && (filter_var($provider, FILTER_VALIDATE_URL) !== false)) {
            // check the uri schema
            if (preg_match('/^https?:\/\//', $provider) === 1) {
                $requestManager = new HttpRequestManager($provider);

                $this->provider = new HttpProvider($requestManager);
            }
        } elseif ($provider instanceof Provider) {
            $this->provider = $provider;
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        if (empty($this->provider)) {
            throw new RuntimeException('Please set provider first.');
        }

        $class = explode('\\', get_class());

        if (preg_match('/^[a-zA-Z0-9]+$/', $name) !== 1) {
            return;
        }

        $method = strtolower($class[1]) . '_' . $name;

        if (!in_array($method, $this->allowedMethods)) {
            throw new RuntimeException('Unallowed rpc method: ' . $method);
        }
        if ($this->provider->isBatch) {
            $callback = null;
        } else {
            $callback = array_pop($arguments);

            if (is_callable($callback) !== true) {
                throw new InvalidArgumentException('The last param must be callback function.');
            }
        }

        if (!array_key_exists($method, $this->methods)) {
            // new the method
            $methodClass = sprintf("\Web3\Methods\%s\%s", ucfirst($class[1]), ucfirst($name));
            $methodObject = new $methodClass($method, $arguments);
            $this->methods[$method] = $methodObject;
        } else {
            $methodObject = $this->methods[$method];
        }

        if (!$methodObject->validate($arguments)) {
            return;
        }

        $inputs = $methodObject->transform($arguments, $methodObject->inputFormatters);
        $methodObject->arguments = $inputs;
        $this->provider->send($methodObject, $callback);
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

    /**
     * @param bool $status
     * @return void
     */
    public function batch($status)
    {
        $status = is_bool($status);

        $this->provider->batch($status);
    }
}
