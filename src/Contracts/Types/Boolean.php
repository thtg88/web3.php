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

use InvalidArgumentException;
use Web3\Contracts\SolidityType;

class Boolean extends SolidityType implements IType
{
    public function isType(string $name): bool
    {
        return preg_match('/^bool(\[([0-9]*)\])*$/', $name) === 1;
    }

    public function isDynamicType(): bool
    {
        return false;
    }

    public function inputFormat($value, $name): string
    {
        if (!is_bool($value)) {
            throw new InvalidArgumentException('The value to inputFormat function must be boolean.');
        }

        $value = (int) $value;

        return '000000000000000000000000000000000000000000000000000000000000000' . $value;
    }

    /**
     * @param string $name
     * @return string
     */
    public function outputFormat($value, $name)
    {
        $value = (int) mb_substr($value, 63, 1);

        return (bool) $value;
    }
}
