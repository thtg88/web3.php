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

use Web3\Utils;

class TagValidator implements IValidator
{
    /**
     * @param string $value
     */
    public static function validate($value): bool
    {
        $value = Utils::toString($value);

        $tags = [
            'latest',
            'earliest',
            'pending',
        ];

        return in_array($value, $tags);
    }
}
