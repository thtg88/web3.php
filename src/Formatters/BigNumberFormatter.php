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

use phpseclib\Math\BigInteger;
use Web3\Utils;

class BigNumberFormatter implements IFormatter
{
    public static function format($value): BigInteger
    {
        $value = Utils::toString($value);
        $bn = Utils::toBn($value);

        return $bn;
    }
}
