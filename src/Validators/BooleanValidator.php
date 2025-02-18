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

class BooleanValidator implements IValidator
{
    public static function validate(mixed $value): bool
    {
        return is_bool($value);
    }
}
