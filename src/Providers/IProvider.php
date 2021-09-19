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

use Web3\Methods\IMethod;

interface IProvider
{
    public function send(IMethod $method): ?array;

    /**
     * @param bool $status
     */
    public function batch($status): void;

    public function execute(): array;
}
