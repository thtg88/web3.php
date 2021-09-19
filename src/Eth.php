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

    public function accounts(): array|self|null
    {
        $result = $this->provider->send(new Accounts());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function blockNumber(): array|self|null
    {
        $result = $this->provider->send(new BlockNumber());

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

    public function coinbase(): array|self|null
    {
        $result = $this->provider->send(new Coinbase());

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

    public function gasPrice(): array|self|null
    {
        $result = $this->provider->send(new GasPrice());

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

    public function getBlockByHash(
        string $block_hash,
        bool $hydrated_transactions,
    ): array|self|null {
        $result = $this->provider->send(new GetBlockByHash([
            $block_hash,
            $hydrated_transactions,
        ]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockByNumber(
        string $block_hash,
        bool $hydrated_transactions,
    ): array|self|null {
        $result = $this->provider->send(new GetBlockByNumber([
            $block_hash,
            $hydrated_transactions,
        ]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockTransactionCountByHash(string $block_hash): array|self|null
    {
        $result = $this->provider->send(new GetBlockTransactionCountByHash([$block_hash]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getBlockTransactionCountByNumber($block_number): array|self|null
    {
        $result = $this->provider->send(new GetBlockTransactionCountByNumber([$block_number]));

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

    public function getFilterChanges(string $filter_identifier): array|self|null
    {
        $result = $this->provider->send(new GetFilterChanges([$filter_identifier]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getFilterLogs(string $filter_identifier): array|self|null
    {
        $result = $this->provider->send(new GetFilterLogs([$filter_identifier]));

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

    public function getTransactionByBlockHashAndIndex(
        string $block_hash,
        $transaction_index,
    ): array|self|null {
        $result = $this->provider->send(new GetTransactionByBlockHashAndIndex([
            $block_hash,
            $transaction_index,
        ]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionByBlockNumberAndIndex(
        $block_number,
        $transaction_index,
    ): array|self|null {
        $result = $this->provider->send(new GetTransactionByBlockNumberAndIndex([
            $block_number,
            $transaction_index,
        ]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getTransactionByHash(string $transaction_hash): array|self|null
    {
        $result = $this->provider->send(new GetTransactionByHash([$transaction_hash]));

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

    public function getTransactionReceipt(string $transaction_hash): array|self|null
    {
        $result = $this->provider->send(new GetTransactionReceipt([$transaction_hash]));

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

    public function getUncleCountByBlockHash(string $block_hash): array|self|null
    {
        $result = $this->provider->send(new GetUncleCountByBlockHash([$block_hash]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function getUncleCountByBlockNumber($block_number): array|self|null
    {
        $result = $this->provider->send(new GetUncleCountByBlockNumber([$block_number]));

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

    public function hashrate(): array|self|null
    {
        $result = $this->provider->send(new Hashrate());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function mining(): array|self|null
    {
        $result = $this->provider->send(new Mining());

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

    public function newPendingTransactionFilter(): array|self|null
    {
        $result = $this->provider->send(new NewPendingTransactionFilter());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function protocolVersion(): array|self|null
    {
        $result = $this->provider->send(new ProtocolVersion());

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

    public function sign(string $address, string $message): array|self|null
    {
        $result = $this->provider->send(new Sign([$address, $message]));

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

    public function submitHashrate(string $hashrate, string $id): array|self|null
    {
        $result = $this->provider->send(new SubmitHashrate([$hashrate, $id]));

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function syncing(): array|self|null
    {
        $result = $this->provider->send(new Syncing());

        if ($this->provider->isBatch) {
            return $this;
        }

        return $result;
    }

    public function uninstallFilter(string $filter_identifier): array|self|null
    {
        $result = $this->provider->send(new UninstallFilter([$filter_identifier]));

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

    public function __set(string $name, $value)
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

    /**
     * @param bool $status
     */
    public function batch($status): void
    {
        $status = is_bool($status);

        $this->provider->batch($status);
    }
}
