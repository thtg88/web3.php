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
     * @return bool
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$value]);
        }

        return false;
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
