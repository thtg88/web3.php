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

use Web3\Contracts\SolidityType;
use Web3\Formatters\IntegerFormatter;
use Web3\Formatters\BigNumberFormatter;

class Uinteger extends SolidityType implements IType
{
    public function isType(string $name): bool
    {
        return preg_match('/^uint([0-9]{1,})?(\[([0-9]*)\])*$/', $name) === 1;
    }

    public function isDynamicType(): bool
    {
        return false;
    }

    /**
     * @param string $name
     */
    public function inputFormat($value, $name): string
    {
        return IntegerFormatter::format($value);
    }

    /**
     * @param string $name
     */
    public function outputFormat($value, $name): string
    {
        $match = [];

        if (preg_match('/^[0]+([a-f0-9]+)$/', $value, $match) === 1) {
            // due to value without 0x prefix, we will parse as decimal
            $value = '0x' . $match[1];
        }

        return BigNumberFormatter::format($value);
    }
}
