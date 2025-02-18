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

class PostFormatter implements IFormatter
{
    public static function format($value)
    {
        if (isset($value['priority'])) {
            $value['priority'] = QuantityFormatter::format($value['priority']);
        }

        if (isset($value['ttl'])) {
            $value['ttl'] = QuantityFormatter::format($value['ttl']);
        }

        return $value;
    }
}
