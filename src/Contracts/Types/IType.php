<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Contracts\Types;

interface IType
{
    public function isType(string $name): bool;
    public function isDynamicType(): bool;

    /**
     * @param string $name
     * @return string
     */
    public function inputFormat($value, $name);
}
