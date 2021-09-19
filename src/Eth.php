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

    public function accounts(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Accounts($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function blockNumber(...$arguments): array|self|null
    {
        $result = $this->provider->send(new BlockNumber($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function call(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Call($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function coinbase(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Coinbase($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function compileLLL(...$arguments): array|self|null
    {
        $result = $this->provider->send(new CompileLLL($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function compileSerpent(...$arguments): array|self|null
    {
        $result = $this->provider->send(new CompileSerpent($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function compileSolidity(...$arguments): array|self|null
    {
        $result = $this->provider->send(new CompileSolidity($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function estimateGas(...$arguments): array|self|null
    {
        $result = $this->provider->send(new EstimateGas($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function gasPrice(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GasPrice($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBalance(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetBalance($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockByHash(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetBlockByHash($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockByNumber(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetBlockByNumber($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockTransactionCountByHash(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetBlockTransactionCountByHash($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockTransactionCountByNumber(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetBlockTransactionCountByNumber($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getCode(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetCode($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getFilterChanges(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetFilterChanges($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getFilterLogs(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetFilterLogs($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getLogs(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetLogs($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getStorageAt(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetStorageAt($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionByBlockHashAndIndex(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetTransactionByBlockHashAndIndex($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionByBlockNumberAndIndex(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetTransactionByBlockNumberAndIndex($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionByHash(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetTransactionByHash($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionCount(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetTransactionCount($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionReceipt(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetTransactionReceipt($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getUncleByBlockHashAndIndex(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetUncleByBlockHashAndIndex($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getUncleByBlockNumberAndIndex(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetUncleByBlockNumberAndIndex($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getUncleCountByBlockHash(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetUncleCountByBlockHash($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getUncleCountByBlockNumber(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetUncleCountByBlockNumber($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getWork(...$arguments): array|self|null
    {
        $result = $this->provider->send(new GetWork($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function hashrate(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Hashrate($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function mining(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Mining($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newBlockFilter(...$arguments): array|self|null
    {
        $result = $this->provider->send(new NewBlockFilter($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newFilter(...$arguments): array|self|null
    {
        $result = $this->provider->send(new NewFilter($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function newPendingTransactionFilter(...$arguments): array|self|null
    {
        $result = $this->provider->send(new NewPendingTransactionFilter($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function protocolVersion(...$arguments): array|self|null
    {
        $result = $this->provider->send(new ProtocolVersion($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function sendRawTransaction(...$arguments): array|self|null
    {
        $result = $this->provider->send(new SendRawTransaction($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function sendTransaction(...$arguments): array|self|null
    {
        $result = $this->provider->send(new SendTransaction($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function sign(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Sign($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function submitWork(...$arguments): array|self|null
    {
        $result = $this->provider->send(new SubmitWork($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function submitHashrate(...$arguments): array|self|null
    {
        $result = $this->provider->send(new SubmitHashrate($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function syncing(...$arguments): array|self|null
    {
        $result = $this->provider->send(new Syncing($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function uninstallFilter(...$arguments): array|self|null
    {
        $result = $this->provider->send(new UninstallFilter($arguments));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
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
