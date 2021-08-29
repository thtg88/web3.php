<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Validators;

class HexValidator
{
    /**
     * @param string $value
     */
    public static function validate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (preg_match('/^0x[a-fA-F0-9]*$/', $value) >= 1);
    }
}
