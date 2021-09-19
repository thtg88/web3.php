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
use stdClass;
use Web3\Contracts\Ethabi;
use Web3\Contracts\Types\Address;
use Web3\Contracts\Types\Boolean;
use Web3\Contracts\Types\Bytes;
use Web3\Contracts\Types\DynamicBytes;
use Web3\Contracts\Types\Integer;
use Web3\Contracts\Types\Str;
use Web3\Contracts\Types\Uinteger;
use Web3\Formatters\AddressFormatter;
use Web3\Providers\HttpProvider;
use Web3\Providers\Provider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Validators\AddressValidator;
use Web3\Validators\HexValidator;
use Web3\Validators\QuantityValidator;
use Web3\Validators\StringValidator;
use Web3\Validators\TagValidator;

class Contract
{
    protected Provider $provider;
    protected array $abi;
    protected array $constructor = [];
    protected array $functions = [];
    protected array $events = [];
    protected string $toAddress;
    protected string $bytecode;
    protected Eth $eth;
    protected Ethabi $ethabi;
    protected string $defaultBlock;

    public function __construct(
        Provider|string $provider,
        string|stdClass|array $abi,
        string $defaultBlock = 'latest'
    ) {
        if (is_string($provider) && (filter_var($provider, FILTER_VALIDATE_URL) !== false)) {
            // check the uri schema
            if (preg_match('/^https?:\/\//', $provider) === 1) {
                $requestManager = new HttpRequestManager($provider);

                $this->provider = new HttpProvider($requestManager);
            }
        } elseif ($provider instanceof Provider) {
            $this->provider = $provider;
        }

        $abiArray = [];
        if (is_string($abi)) {
            $abiArray = json_decode($abi, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new InvalidArgumentException('abi decode error: ' . json_last_error_msg());
            }
        } else {
            $abiArray = Utils::jsonToArray($abi);
        }
        foreach ($abiArray as $item) {
            if (!isset($item['type'])) {
                continue;
            }

            if ($item['type'] === 'function') {
                $this->functions[] = $item;

                continue;
            }

            if ($item['type'] === 'constructor') {
                $this->constructor = $item;

                continue;
            }

            if ($item['type'] === 'event') {
                $this->events[$item['name']] = $item;
            }
        }
        if (TagValidator::validate($defaultBlock) || QuantityValidator::validate($defaultBlock)) {
            $this->defaultBlock = $defaultBlock;
        } else {
            $this->$defaultBlock = 'latest';
        }
        $this->abi = $abiArray;
        $this->eth = new Eth($this->provider);
        $this->ethabi = new Ethabi([
            'address' => new Address(),
            'bool' => new Boolean(),
            'bytes' => new Bytes(),
            'dynamicBytes' => new DynamicBytes(),
            'int' => new Integer(),
            'string' => new Str(),
            'uint' => new Uinteger(),
        ]);
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

    public function getDefaultBlock(): string
    {
        return $this->defaultBlock;
    }

    public function setDefaultBlock($defaultBlock): self
    {
        if (
            TagValidator::validate($defaultBlock) ||
            QuantityValidator::validate($defaultBlock)
        ) {
            $this->defaultBlock = $defaultBlock;
        } else {
            $this->$defaultBlock = 'latest';
        }

        return $this;
    }

    public function getFunctions(): array
    {
        return $this->functions;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getToAddress(): string
    {
        return $this->toAddress;
    }

    public function getConstructor(): array
    {
        return $this->constructor;
    }

    public function getAbi(): array
    {
        return $this->abi;
    }

    public function setAbi($abi): self
    {
        return $this->abi($abi);
    }

    public function getEthabi(): Ethabi
    {
        return $this->ethabi;
    }

    public function getEth(): Eth
    {
        return $this->eth;
    }

    public function setBytecode($bytecode): self
    {
        return $this->bytecode($bytecode);
    }

    public function setToAddress($address): self
    {
        return $this->at($address);
    }

    public function at($address): self
    {
        if (AddressValidator::validate($address) === false) {
            throw new InvalidArgumentException('Please make sure address is valid.');
        }

        $this->toAddress = AddressFormatter::format($address);

        return $this;
    }

    public function bytecode($bytecode): self
    {
        if (HexValidator::validate($bytecode) === false) {
            throw new InvalidArgumentException('Please make sure bytecode is valid.');
        }

        $this->bytecode = Utils::stripZero($bytecode);

        return $this;
    }

    public function abi($abi): self
    {
        if (StringValidator::validate($abi) === false) {
            throw new InvalidArgumentException('Please make sure abi is valid.');
        }

        $abiArray = [];

        if (is_string($abi)) {
            $abiArray = json_decode($abi, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new InvalidArgumentException('abi decode error: ' . json_last_error_msg());
            }
        } else {
            $abiArray = Utils::jsonToArray($abi);
        }

        foreach ($abiArray as $item) {
            if (!isset($item['type'])) {
                continue;
            }

            if ($item['type'] === 'function') {
                $this->functions[] = $item;
            } elseif ($item['type'] === 'constructor') {
                $this->constructor = $item;
            } elseif ($item['type'] === 'event') {
                $this->events[$item['name']] = $item;
            }
        }

        $this->abi = $abiArray;

        return $this;
    }

    /**
     * Deploy a contract with params.
     */
    public function new(...$arguments): array
    {
        if (!isset($this->constructor)) {
            throw new RuntimeException('No constructor set.');
        }

        $constructor = $this->constructor;
        $callback = array_pop($arguments);

        $input_count = isset($constructor['inputs']) ? count($constructor['inputs']) : 0;

        if (count($arguments) < $input_count) {
            throw new InvalidArgumentException('Please make sure you have put all constructor params and callback.');
        }

        if (is_callable($callback) !== true) {
            throw new InvalidArgumentException('The last param must be callback function.');
        }

        if (!isset($this->bytecode)) {
            throw new InvalidArgumentException('Please call bytecode($bytecode) before new().');
        }

        $params = array_splice($arguments, 0, $input_count);
        $data = $this->ethabi->encodeParameters($constructor, $params);
        $transaction = [];

        if (count($arguments) > 0) {
            $transaction = $arguments[0];
        }

        $transaction['data'] = '0x' . $this->bytecode . Utils::stripZero($data);

        [$err, $transaction] = $this->eth->sendTransaction($transaction, function () {
        });

        if ($err !== null) {
            call_user_func($callback, $err, null);

            return [$err, null];
        }

        call_user_func($callback, null, $transaction);

        return [null, $transaction];
    }

    public function send(...$arguments): array
    {
        if (!isset($this->functions)) {
            throw new RuntimeException('No functions set.');
        }

        $method = array_splice($arguments, 0, 1)[0];
        $callback = array_pop($arguments);

        if (!is_string($method)) {
            throw new InvalidArgumentException('Please make sure the method is string.');
        }

        $functions = [];
        foreach ($this->functions as $function) {
            if ($function['name'] === $method) {
                $functions[] = $function;
            }
        };
        if (count($functions) < 1) {
            throw new InvalidArgumentException('Please make sure the method exists.');
        }
        if (is_callable($callback) !== true) {
            throw new InvalidArgumentException('The last param must be callback function.');
        }

        // check the last one in arguments is transaction object
        $argsLen = count($arguments);
        $transaction = [];
        $hasTransaction = false;

        if ($argsLen > 0) {
            $transaction = $arguments[$argsLen - 1];
        }

        if (
            isset($transaction['from']) ||
            isset($transaction['to']) ||
            isset($transaction['gas']) ||
            isset($transaction['gasPrice']) ||
            isset($transaction['value']) ||
            isset($transaction['data']) ||
            isset($transaction['nonce'])
        ) {
            $hasTransaction = true;
        } else {
            $transaction = [];
        }

        $params = [];
        $data = '';
        $functionName = '';
        foreach ($functions as $function) {
            if ($hasTransaction) {
                if ($argsLen - 1 !== count($function['inputs'])) {
                    continue;
                }

                $paramsLen = $argsLen - 1;
            } else {
                if ($argsLen !== count($function['inputs'])) {
                    continue;
                }

                $paramsLen = $argsLen;
            }

            try {
                $params = array_splice($arguments, 0, $paramsLen);
                $data = $this->ethabi->encodeParameters($function, $params);
                $functionName = Utils::jsonMethodToString($function);
            } catch (InvalidArgumentException) {
                continue;
            }

            break;
        }
        if (empty($data) || empty($functionName)) {
            throw new InvalidArgumentException('Please make sure you have put all function params and callback.');
        }

        $functionSignature = $this->ethabi->encodeFunctionSignature($functionName);
        $transaction['to'] = $this->toAddress;
        $transaction['data'] = $functionSignature . Utils::stripZero($data);

        [$err, $transaction] = $this->eth->sendTransaction($transaction, function () {
        });

        if ($err !== null) {
            call_user_func($callback, $err, null);

            return [$err, null];
        }

        call_user_func($callback, null, $transaction);

        return [null, $transaction];
    }

    public function call(...$arguments): array
    {
        if (!isset($this->functions)) {
            throw new RuntimeException('No functions set.');
        }

        $method = array_splice($arguments, 0, 1)[0];
        $callback = array_pop($arguments);

        if (!is_string($method)) {
            throw new InvalidArgumentException('Please make sure the method is string.');
        }

        $functions = array_filter(
            $this->functions,
            fn ($function) => $function['name'] === $method
        );
        if (count($functions) < 1) {
            throw new InvalidArgumentException('Please make sure the method exists.');
        }
        if (is_callable($callback) !== true) {
            throw new InvalidArgumentException('The last param must be callback function.');
        }

        // check the arguments
        $argsLen = count($arguments);
        $transaction = [];
        $defaultBlock = $this->defaultBlock;
        $params = [];
        $data = '';
        $functionName = '';
        foreach ($functions as $function) {
            try {
                $paramsLen = count($function['inputs']);
                $params = array_slice($arguments, 0, $paramsLen);
                $data = $this->ethabi->encodeParameters($function, $params);
                $functionName = Utils::jsonMethodToString($function);
            } catch (InvalidArgumentException) {
                continue;
            }

            break;
        }
        if (empty($data) || empty($functionName)) {
            throw new InvalidArgumentException('Please make sure you have put all function params and callback.');
        }
        // remove arguments
        array_splice($arguments, 0, $paramsLen);
        $argsLen -= $paramsLen;

        if ($argsLen > 1) {
            $defaultBlock = $arguments[$argsLen - 1];
            $transaction = $arguments[$argsLen - 2];
        } elseif ($argsLen > 0) {
            if (is_array($arguments[$argsLen - 1])) {
                $transaction = $arguments[$argsLen - 1];
            } else {
                $defaultBlock = $arguments[$argsLen - 1];
            }
        }
        if (
            !TagValidator::validate($defaultBlock) &&
            !QuantityValidator::validate($defaultBlock)
        ) {
            $defaultBlock = $this->defaultBlock;
        }

        if (
            !is_array($transaction) &&
            !isset($transaction['from']) &&
            !isset($transaction['to']) &&
            !isset($transaction['gas']) &&
            !isset($transaction['gasPrice']) &&
            !isset($transaction['value']) &&
            !isset($transaction['data']) &&
            !isset($transaction['nonce'])
        ) {
            $transaction = [];
        }

        $functionSignature = $this->ethabi->encodeFunctionSignature($functionName);
        $transaction['to'] = $this->toAddress;
        $transaction['data'] = $functionSignature . Utils::stripZero($data);

        [$err, $transaction] = $this->eth->call($transaction, $defaultBlock, function () {
        });

        if ($err !== null) {
            call_user_func($callback, $err, null);

            return [$err, null];
        }

        $decodedTransaction = $this->ethabi->decodeParameters($function, $transaction);

        call_user_func($callback, null, $decodedTransaction);

        return [null, $transaction];
    }

    public function estimateGas(...$arguments): ?array
    {
        // TODO: throw?
        if (!isset($this->functions) && !isset($this->constructor)) {
            return null;
        }

        $callback = array_pop($arguments);

        if (empty($this->toAddress) && !empty($this->bytecode)) {
            $constructor = $this->constructor;

            if (count($arguments) < count($constructor['inputs'])) {
                throw new InvalidArgumentException('Please make sure you have put all constructor params and callback.');
            }

            if (is_callable($callback) !== true) {
                throw new InvalidArgumentException('The last param must be callback function.');
            }

            if (!isset($this->bytecode)) {
                throw new InvalidArgumentException('Please call bytecode($bytecode) before estimateGas().');
            }

            $params = array_splice($arguments, 0, count($constructor['inputs']));
            $data = $this->ethabi->encodeParameters($constructor, $params);
            $transaction = [];

            if (count($arguments) > 0) {
                $transaction = $arguments[0];
            }
            $transaction['data'] = '0x' . $this->bytecode . Utils::stripZero($data);
        } else {
            $method = array_splice($arguments, 0, 1)[0];

            if (!is_string($method)) {
                throw new InvalidArgumentException('Please make sure the method is string.');
            }

            $functions = array_filter(
                $this->functions,
                fn ($function) => $function['name'] === $method
            );
            if (count($functions) < 1) {
                throw new InvalidArgumentException('Please make sure the method exists.');
            }
            if (is_callable($callback) !== true) {
                throw new InvalidArgumentException('The last param must be callback function.');
            }

            // check the last one in arguments is transaction object
            $argsLen = count($arguments);
            $transaction = [];
            $hasTransaction = false;

            if ($argsLen > 0) {
                $transaction = $arguments[$argsLen - 1];
            }
            if (
                isset($transaction['from']) ||
                isset($transaction['to']) ||
                isset($transaction['gas']) ||
                isset($transaction['gasPrice']) ||
                isset($transaction['value']) ||
                isset($transaction['data']) ||
                isset($transaction['nonce'])
            ) {
                $hasTransaction = true;
            } else {
                $transaction = [];
            }

            $params = [];
            $data = '';
            $functionName = '';
            foreach ($functions as $function) {
                if ($hasTransaction) {
                    if ($argsLen - 1 !== count($function['inputs'])) {
                        continue;
                    }

                    $paramsLen = $argsLen - 1;
                } else {
                    if ($argsLen !== count($function['inputs'])) {
                        continue;
                    }

                    $paramsLen = $argsLen;
                }

                try {
                    $params = array_splice($arguments, 0, $paramsLen);
                    $data = $this->ethabi->encodeParameters($function, $params);
                    $functionName = Utils::jsonMethodToString($function);
                } catch (InvalidArgumentException) {
                    continue;
                }

                break;
            }
            if (empty($data) || empty($functionName)) {
                throw new InvalidArgumentException('Please make sure you have put all function params and callback.');
            }

            $functionSignature = $this->ethabi->encodeFunctionSignature($functionName);
            $transaction['to'] = $this->toAddress;
            $transaction['data'] = $functionSignature . Utils::stripZero($data);
        }

        [$err, $gas] = $this->eth->estimateGas($transaction, function () {
        });

        if ($err !== null) {
            call_user_func($callback, $err, null);

            return [$err, null];
        }

        call_user_func($callback, null, $gas);

        return [null, $gas];
    }

    /**
     * Get the function method call data.
     * With this function, you can send signed contract function transaction.
     * 1. Get the funtion data with params.
     * 2. Sign the data with user private key.
     * 3. Call sendRawTransaction.
     */
    public function getData(...$arguments): ?string
    {
        if (!isset($this->functions) && !isset($this->constructor)) {
            return null;
        }

        if (empty($this->toAddress) && !empty($this->bytecode)) {
            $constructor = $this->constructor;

            if (count($arguments) < count($constructor['inputs'])) {
                throw new InvalidArgumentException('Please make sure you have put all constructor params and callback.');
            }
            if (!isset($this->bytecode)) {
                throw new InvalidArgumentException('Please call bytecode($bytecode) before getData().');
            }
            $params = array_splice($arguments, 0, count($constructor['inputs']));
            $data = $this->ethabi->encodeParameters($constructor, $params);

            return $this->bytecode . Utils::stripZero($data);
        }

        $method = array_splice($arguments, 0, 1)[0];

        if (!is_string($method)) {
            throw new InvalidArgumentException('Please make sure the method is string.');
        }

        $functions = [];
        foreach ($this->functions as $function) {
            if ($function['name'] === $method) {
                $functions[] = $function;
            }
        };
        if (count($functions) < 1) {
            throw new InvalidArgumentException('Please make sure the method exists.');
        }

        $params = $arguments;
        $data = '';
        $functionName = '';
        foreach ($functions as $function) {
            if (count($arguments) !== count($function['inputs'])) {
                continue;
            }

            try {
                $data = $this->ethabi->encodeParameters($function, $params);
                $functionName = Utils::jsonMethodToString($function);
            } catch (InvalidArgumentException) {
                continue;
            }

            break;
        }
        if (empty($data) || empty($functionName)) {
            throw new InvalidArgumentException('Please make sure you have put all function params and callback.');
        }

        $functionSignature = $this->ethabi->encodeFunctionSignature($functionName);

        return Utils::stripZero($functionSignature) . Utils::stripZero($data);
    }
}
