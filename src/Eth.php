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
use Web3\Methods\Eth\Accounts;
use Web3\Methods\Eth\BlockNumber;
use Web3\Methods\Eth\Call;
use Web3\Methods\Eth\Coinbase;
use Web3\Methods\Eth\CompileLLL;
use Web3\Methods\Eth\CompileSerpent;
use Web3\Methods\Eth\CompileSolidity;
use Web3\Methods\Eth\EstimateGas;
use Web3\Methods\Eth\GasPrice;
use Web3\Methods\Eth\GetBalance;
use Web3\Methods\Eth\GetBlockByHash;
use Web3\Methods\Eth\GetBlockByNumber;
use Web3\Methods\Eth\GetBlockTransactionCountByHash;
use Web3\Methods\Eth\GetBlockTransactionCountByNumber;
use Web3\Methods\Eth\GetCode;
use Web3\Methods\Eth\GetFilterChanges;
use Web3\Methods\Eth\GetFilterLogs;
use Web3\Methods\Eth\GetLogs;
use Web3\Methods\Eth\GetStorageAt;
use Web3\Methods\Eth\GetTransactionByBlockHashAndIndex;
use Web3\Methods\Eth\GetTransactionByBlockNumberAndIndex;
use Web3\Methods\Eth\GetTransactionByHash;
use Web3\Methods\Eth\GetTransactionCount;
use Web3\Methods\Eth\GetTransactionReceipt;
use Web3\Methods\Eth\GetUncleByBlockHashAndIndex;
use Web3\Methods\Eth\GetUncleByBlockNumberAndIndex;
use Web3\Methods\Eth\GetUncleCountByBlockHash;
use Web3\Methods\Eth\GetUncleCountByBlockNumber;
use Web3\Methods\Eth\GetWork;
use Web3\Methods\Eth\Hashrate;
use Web3\Methods\Eth\Mining;
use Web3\Methods\Eth\NewBlockFilter;
use Web3\Methods\Eth\NewFilter;
use Web3\Methods\Eth\NewPendingTransactionFilter;
use Web3\Methods\Eth\ProtocolVersion;
use Web3\Methods\Eth\SendRawTransaction;
use Web3\Methods\Eth\SendTransaction;
use Web3\Methods\Eth\Sign;
use Web3\Methods\Eth\SubmitHashrate;
use Web3\Methods\Eth\SubmitWork;
use Web3\Methods\Eth\Syncing;
use Web3\Methods\Eth\UninstallFilter;
use Web3\Methods\IMethod;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Eth
{
    protected Provider $provider;

    private array $methods = [];
    private ?IMethod $method;

    /** @var callable */
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
        }

        throw new InvalidArgumentException('Please set a valid provider.');
    }

    public function accounts(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('accounts', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Accounts(arguments: $arguments);

        $this->send($callback);
    }

    public function blockNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('blockNumber', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new BlockNumber(arguments: $arguments);

        $this->send($callback);
    }

    public function call(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('call', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Call(arguments: $arguments);

        $this->send($callback);
    }

    public function coinbase(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('coinbase', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Coinbase(arguments: $arguments);

        $this->send($callback);
    }

    public function compileLLL(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('compileLLL', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new CompileLLL(arguments: $arguments);

        $this->send($callback);
    }

    public function compileSerpent(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('compileSerpent', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new CompileSerpent(arguments: $arguments);

        $this->send($callback);
    }

    public function compileSolidity(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('compileSolidity', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new CompileSolidity(arguments: $arguments);

        $this->send($callback);
    }

    public function estimateGas(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('estimateGas', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new EstimateGas(arguments: $arguments);

        $this->send($callback);
    }

    public function gasPrice(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('gasPrice', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GasPrice(arguments: $arguments);

        $this->send($callback);
    }

    public function getBalance(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getBalance', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetBalance(arguments: $arguments);

        $this->send($callback);
    }

    public function getBlockByHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getBlockByHash', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetBlockByHash(arguments: $arguments);

        $this->send($callback);
    }

    public function getBlockByNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getBlockByNumber', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetBlockByNumber(arguments: $arguments);

        $this->send($callback);
    }

    public function getBlockTransactionCountByHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getBlockTransactionCountByHash', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetBlockTransactionCountByHash(arguments: $arguments);

        $this->send($callback);
    }

    public function getBlockTransactionCountByNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getBlockTransactionCountByNumber', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetBlockTransactionCountByNumber(arguments: $arguments);

        $this->send($callback);
    }

    public function getCode(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getCode', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetCode(arguments: $arguments);

        $this->send($callback);
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

    public function getFilterLogs(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getFilterLogs', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetFilterLogs(arguments: $arguments);

        $this->send($callback);
    }

    public function getLogs(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getLogs', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetLogs(arguments: $arguments);

        $this->send($callback);
    }

    public function getStorageAt(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getStorageAt', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetStorageAt(arguments: $arguments);

        $this->send($callback);
    }

    public function getTransactionByBlockHashAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getTransactionByBlockHashAndIndex', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetTransactionByBlockHashAndIndex(arguments: $arguments);

        $this->send($callback);
    }

    public function getTransactionByBlockNumberAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getTransactionByBlockNumberAndIndex', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetTransactionByBlockNumberAndIndex(arguments: $arguments);

        $this->send($callback);
    }

    public function getTransactionByHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getTransactionByHash', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetTransactionByHash(arguments: $arguments);

        $this->send($callback);
    }

    public function getTransactionCount(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getTransactionCount', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetTransactionCount(arguments: $arguments);

        $this->send($callback);
    }

    public function getTransactionReceipt(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getTransactionReceipt', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetTransactionReceipt(arguments: $arguments);

        $this->send($callback);
    }

    public function getUncleByBlockHashAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getUncleByBlockHashAndIndex', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetUncleByBlockHashAndIndex(arguments: $arguments);

        $this->send($callback);
    }

    public function getUncleByBlockNumberAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getUncleByBlockNumberAndIndex', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetUncleByBlockNumberAndIndex(arguments: $arguments);

        $this->send($callback);
    }

    public function getUncleCountByBlockHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getUncleCountByBlockHash', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetUncleCountByBlockHash(arguments: $arguments);

        $this->send($callback);
    }

    public function getUncleCountByBlockNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getUncleCountByBlockNumber', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetUncleCountByBlockNumber(arguments: $arguments);

        $this->send($callback);
    }

    public function getWork(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('getWork', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new GetWork(arguments: $arguments);

        $this->send($callback);
    }

    public function hashrate(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('hashrate', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Hashrate(arguments: $arguments);

        $this->send($callback);
    }

    public function mining(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('mining', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Mining(arguments: $arguments);

        $this->send($callback);
    }

    public function newBlockFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('newBlockFilter', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new NewBlockFilter(arguments: $arguments);

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

    public function newPendingTransactionFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('newPendingTransactionFilter', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new NewPendingTransactionFilter(arguments: $arguments);

        $this->send($callback);
    }

    public function protocolVersion(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('protocolVersion', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new ProtocolVersion(arguments: $arguments);

        $this->send($callback);
    }

    public function sendRawTransaction(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('sendRawTransaction', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new SendRawTransaction(arguments: $arguments);

        $this->send($callback);
    }

    public function sendTransaction(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('sendTransaction', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new SendTransaction(arguments: $arguments);

        $this->send($callback);
    }

    public function sign(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('sign', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Sign(arguments: $arguments);

        $this->send($callback);
    }

    public function submitWork(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('submitWork', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new SubmitWork(arguments: $arguments);

        $this->send($callback);
    }

    public function submitHashrate(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('submitHashrate', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new SubmitHashrate(arguments: $arguments);

        $this->send($callback);
    }

    public function syncing(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->__call('syncing', $arguments);

            return;
        }

        $callback = array_pop($arguments);

        $this->method = new Syncing(arguments: $arguments);

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

    public function send(callable $callback): void
    {
        if ($this->method === null) {
            throw new RuntimeException('Please set a method.');
        }

        $this->provider->send($this->method, $callback);

        $this->method = null;
        $this->callback = null;
    }

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments): void
    {
        $method_name = 'eth_' . $name;

        if (!array_key_exists($method_name, $this->methods)) {
            // new the method
            $methodClass = sprintf("\Web3\Methods\Eth\%s", ucfirst($name));
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
