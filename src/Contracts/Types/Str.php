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

use Web3\Utils;
use Web3\Contracts\SolidityType;
use Web3\Formatters\IntegerFormatter;
use Web3\Formatters\BigNumberFormatter;

class Str extends SolidityType implements IType
{
    public function isType(string $name): bool
    {
        return preg_match('/^string(\[([0-9]*)\])*$/', $name) === 1;
    }

    public function isDynamicType(): bool
    {
        return true;
    }

    public function inputFormat($value, $name): string
    {
        $value = Utils::toHex($value);
        $prefix = IntegerFormatter::format(mb_strlen($value) / 2);
        $l = floor((mb_strlen($value) + 63) / 64);
        $padding = (($l * 64 - mb_strlen($value) + 1) >= 0) ? $l * 64 - mb_strlen($value) : 0;

        return $prefix . $value . implode('', array_fill(0, $padding, '0'));
    }

    /**
     * @param string $name
     */
    public function outputFormat($value, $name): string
    {
        $strLen = mb_substr($value, 0, 64);
        $strValue = mb_substr($value, 64);
        $match = [];

        if (preg_match('/^[0]+([a-f0-9]+)$/', $strLen, $match) === 1) {
            $strLen = BigNumberFormatter::format('0x' . $match[1])->toString();
        }

        $strValue = mb_substr($strValue, 0, (int) $strLen * 2);

        return Utils::hexToBin($strValue);
    }
}
