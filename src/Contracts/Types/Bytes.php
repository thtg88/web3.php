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
use Web3\Utils;
use Web3\Contracts\SolidityType;

class Bytes extends SolidityType implements IType
{
    public function isType(string $name): bool
    {
        return preg_match('/^bytes([0-9]{1,})(\[([0-9]*)\])*$/', $name) === 1;
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
        if (!Utils::isHex($value)) {
            throw new InvalidArgumentException('The value to inputFormat must be hex bytes.');
        }

        $value = Utils::stripZero($value);

        if (mb_strlen($value) % 2 !== 0) {
            $value = '0' . $value;
            // throw new InvalidArgumentException('The value to inputFormat has invalid length. Value: ' . $value);
        }

        if (mb_strlen($value) > 64) {
            throw new InvalidArgumentException('The value to inputFormat is too long.');
        }

        $l = floor((mb_strlen($value) + 63) / 64);
        $padding = (($l * 64 - mb_strlen($value) + 1) >= 0) ? $l * 64 - mb_strlen($value) : 0;

        return $value . implode('', array_fill(0, $padding, '0'));
    }

    /**
     * @param string $name
     */
    public function outputFormat($value, $name): string
    {
        $checkZero = str_replace('0', '', $value);

        if (empty($checkZero)) {
            return '0';
        }

        if (preg_match('/^bytes([0-9]*)/', $name, $match) === 1) {
            $size = intval($match[1]);
            $length = 2 * $size;
            $value = mb_substr($value, 0, $length);
        }

        return '0x' . $value;
    }
}
