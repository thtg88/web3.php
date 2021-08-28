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

class Provider
{
    /**
     * requestManager
     *
     * @var \Web3\RequestManagers\RequestManager
     */
    protected $requestManager;

    /**
     * isBatch
     *
     * @var bool
     */
    protected $isBatch = false;

    protected array $batch = [];

    protected string $rpcVersion = '2.0';

    /**
     * id
     *
     * @var integer
     */
    protected $id = 0;

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

    /**
     * @return \Web3\RequestManagers\RequestManager
     */
    public function getRequestManager()
    {
        return $this->requestManager;
    }

    /**
     * @return bool
     */
    public function getIsBatch()
    {
        return $this->isBatch;
    }
}
