<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods;

use InvalidArgumentException;

abstract class JSONRPC implements IRPC
{
    protected int $id = 0;
    protected string $rpcVersion = '2.0';
    protected string $method = '';
    protected array $arguments = [];

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
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

    /**
     * @return string
     */
    public function __toString()
    {
        $payload = $this->toPayload();

        return json_encode($payload);
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRpcVersion(): string
    {
        return $this->rpcVersion;
    }

    abstract public function getMethod(): string;

    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function toPayload(): array
    {
        if (empty($this->id)) {
            $id = rand();
        } else {
            $id = $this->id;
        }

        $rpc = [
            'id' => $id,
            'jsonrpc' => $this->rpcVersion,
            'method' => $this->getMethod(),
        ];
        if (count($this->arguments) > 0) {
            $rpc['params'] = $this->arguments;
        }

        return $rpc;
    }

    public function toPayloadString(): string
    {
        return json_encode($this->toPayload());
    }
}
