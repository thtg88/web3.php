<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\RequestManagers;

abstract class RequestManager implements IRequestManager
{
    protected string $host;
    protected float $timeout;

    public function __construct(string $host, float $timeout=1)
    {
        $this->host = $host;
        $this->timeout = $timeout;
    }

    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);

        return $this->$method();
    }

    public function __set(string $name, mixed $value)
    {
        $method = 'set' . ucfirst($name);

        return $this->$method($value);
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }
}
