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
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;

class Eth
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

    public function accounts(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Accounts($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Accounts($arguments), $callback);
    }

    public function blockNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new BlockNumber($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new BlockNumber($arguments), $callback);
    }

    public function call(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Call($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Call($arguments), $callback);
    }

    public function coinbase(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Coinbase($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Coinbase($arguments), $callback);
    }

    public function compileLLL(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new CompileLLL($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new CompileLLL($arguments), $callback);
    }

    public function compileSerpent(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new CompileSerpent($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new CompileSerpent($arguments), $callback);
    }

    public function compileSolidity(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new CompileSolidity($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new CompileSolidity($arguments), $callback);
    }

    public function estimateGas(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new EstimateGas($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new EstimateGas($arguments), $callback);
    }

    public function gasPrice(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GasPrice($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GasPrice($arguments), $callback);
    }

    public function getBalance(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetBalance($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetBalance($arguments), $callback);
    }

    public function getBlockByHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetBlockByHash($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetBlockByHash($arguments), $callback);
    }

    public function getBlockByNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetBlockByNumber($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetBlockByNumber($arguments), $callback);
    }

    public function getBlockTransactionCountByHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetBlockTransactionCountByHash($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetBlockTransactionCountByHash($arguments), $callback);
    }

    public function getBlockTransactionCountByNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetBlockTransactionCountByNumber($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetBlockTransactionCountByNumber($arguments), $callback);
    }

    public function getCode(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetCode($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetCode($arguments), $callback);
    }

    public function getFilterChanges(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetFilterChanges($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetFilterChanges($arguments), $callback);
    }

    public function getFilterLogs(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetFilterLogs($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetFilterLogs($arguments), $callback);
    }

    public function getLogs(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetLogs($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetLogs($arguments), $callback);
    }

    public function getStorageAt(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetStorageAt($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetStorageAt($arguments), $callback);
    }

    public function getTransactionByBlockHashAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetTransactionByBlockHashAndIndex($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetTransactionByBlockHashAndIndex($arguments), $callback);
    }

    public function getTransactionByBlockNumberAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetTransactionByBlockNumberAndIndex($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetTransactionByBlockNumberAndIndex($arguments), $callback);
    }

    public function getTransactionByHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetTransactionByHash($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetTransactionByHash($arguments), $callback);
    }

    public function getTransactionCount(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetTransactionCount($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetTransactionCount($arguments), $callback);
    }

    public function getTransactionReceipt(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetTransactionReceipt($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetTransactionReceipt($arguments), $callback);
    }

    public function getUncleByBlockHashAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetUncleByBlockHashAndIndex($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetUncleByBlockHashAndIndex($arguments), $callback);
    }

    public function getUncleByBlockNumberAndIndex(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetUncleByBlockNumberAndIndex($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetUncleByBlockNumberAndIndex($arguments), $callback);
    }

    public function getUncleCountByBlockHash(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetUncleCountByBlockHash($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetUncleCountByBlockHash($arguments), $callback);
    }

    public function getUncleCountByBlockNumber(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetUncleCountByBlockNumber($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetUncleCountByBlockNumber($arguments), $callback);
    }

    public function getWork(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new GetWork($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new GetWork($arguments), $callback);
    }

    public function hashrate(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Hashrate($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Hashrate($arguments), $callback);
    }

    public function mining(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Mining($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Mining($arguments), $callback);
    }

    public function newBlockFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new NewBlockFilter($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new NewBlockFilter($arguments), $callback);
    }

    public function newFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new NewFilter($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new NewFilter($arguments), $callback);
    }

    public function newPendingTransactionFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new NewPendingTransactionFilter($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new NewPendingTransactionFilter($arguments), $callback);
    }

    public function protocolVersion(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new ProtocolVersion($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new ProtocolVersion($arguments), $callback);
    }

    public function sendRawTransaction(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new SendRawTransaction($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new SendRawTransaction($arguments), $callback);
    }

    public function sendTransaction(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new SendTransaction($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new SendTransaction($arguments), $callback);
    }

    public function sign(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Sign($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Sign($arguments), $callback);
    }

    public function submitWork(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new SubmitWork($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new SubmitWork($arguments), $callback);
    }

    public function submitHashrate(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new SubmitHashrate($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new SubmitHashrate($arguments), $callback);
    }

    public function syncing(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new Syncing($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new Syncing($arguments), $callback);
    }

    public function uninstallFilter(...$arguments): void
    {
        if ($this->provider->isBatch) {
            $this->provider->send(new UninstallFilter($arguments));

            return;
        }

        $callback = array_pop($arguments);

        $this->provider->send(new UninstallFilter($arguments), $callback);
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
