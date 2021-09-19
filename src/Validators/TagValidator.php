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
    public const TAGS = [
        'latest',
        'earliest',
        'pending',
    ];

    /**
     * @param string $value
     */
    public static function validate(mixed $value): bool
    {
        $value = Utils::toString($value);

        return in_array($value, self::TAGS);
    }
}
