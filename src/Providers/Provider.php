<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Providers;

use Web3\RequestManagers\RequestManager;

abstract class Provider implements IProvider
{
    protected RequestManager $requestManager;
    protected bool $isBatch = false;
    protected array $batch = [];

    public function __construct(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
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

    public function getRequestManager(): RequestManager
    {
        return $this->requestManager;
    }

    public function getIsBatch(): bool
    {
        return $this->isBatch;
    }
}
