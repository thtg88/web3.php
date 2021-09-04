<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Formatters;

use Web3\Utils;

class AddressFormatter implements IFormatter
{
    /**
     * format
     * to do: iban
     *
     * @return string
     */
    public static function format($value)
    {
        $value = (string) $value;

        if (Utils::isAddress($value)) {
            $value = mb_strtolower($value);

            if (Utils::isZeroPrefixed($value)) {
                return $value;
            }

            return '0x' . $value;
        }

        $value = IntegerFormatter::format($value, 40);

        return '0x' . $value;
    }
}
