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

class Eth
{
    protected Provider $provider;

    private array $methods = [];

    private array $allowedMethods = [
        'eth_protocolVersion',
        'eth_syncing',
        'eth_coinbase',
        'eth_mining',
        'eth_hashrate',
        'eth_gasPrice',
        'eth_accounts',
        'eth_blockNumber',
        'eth_getBalance',
        'eth_getStorageAt',
        'eth_getTransactionCount',
        'eth_getBlockTransactionCountByHash',
        'eth_getBlockTransactionCountByNumber',
        'eth_getUncleCountByBlockHash',
        'eth_getUncleCountByBlockNumber',
        'eth_getUncleByBlockHashAndIndex',
        'eth_getUncleByBlockNumberAndIndex',
        'eth_getCode',
        'eth_sign',
        'eth_sendTransaction',
        'eth_sendRawTransaction',
        'eth_call',
        'eth_estimateGas',
        'eth_getBlockByHash',
        'eth_getBlockByNumber',
        'eth_getTransactionByHash',
        'eth_getTransactionByBlockHashAndIndex',
        'eth_getTransactionByBlockNumberAndIndex',
        'eth_getTransactionReceipt',
        'eth_compileSolidity',
        'eth_compileLLL',
        'eth_compileSerpent',
        'eth_getWork',
        'eth_newFilter',
        'eth_newBlockFilter',
        'eth_newPendingTransactionFilter',
        'eth_uninstallFilter',
        'eth_getFilterChanges',
        'eth_getFilterLogs',
        'eth_getLogs',
        'eth_submitWork',
        'eth_submitHashrate',
    ];

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
        }

        throw new InvalidArgumentException('Please set a valid provider.');
    }
    }

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments): void
    {
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

        if ($methodObject->validate($arguments)) {
            $inputs = $methodObject->transform($arguments, $methodObject->inputFormatters);
            $methodObject->arguments = $inputs;
            $this->provider->send($methodObject, $callback);
        }
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
