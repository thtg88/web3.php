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
use Web3\Utils;
use Web3\Formatters\IntegerFormatter;

class Address extends SolidityType implements IType
{
    public function isType(string $name): bool
    {
        return preg_match('/^address(\[([0-9]*)\])*$/', $name) === 1;
    }

    public function isDynamicType(): bool
    {
        return false;
    }

    /**
     * to do: iban
     *
     * @param string $name
     */
    public function inputFormat($value, $name): string
    {
        $value = (string) $value;

        if (Utils::isAddress($value)) {
            $value = mb_strtolower($value);

            if (Utils::isZeroPrefixed($value)) {
                $value = Utils::stripZero($value);
            }
        }

        $value = IntegerFormatter::format($value);

        return $value;
    }

    /**
     * @param string $name
     */
    public function outputFormat($value, $name): string
    {
        return '0x' . mb_substr($value, 24, 40);
    }
}
