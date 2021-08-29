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

class CallValidator
{
    /**
     * @param array $value
     */
    public static function validate($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        if (isset($value['from']) && AddressValidator::validate($value['from']) === false) {
            return false;
        }

        if (!isset($value['to'])) {
            return false;
        }

        if (AddressValidator::validate($value['to']) === false) {
            return false;
        }

        if (isset($value['gas']) && QuantityValidator::validate($value['gas']) === false) {
            return false;
        }

        if (isset($value['gasPrice']) && QuantityValidator::validate($value['gasPrice']) === false) {
            return false;
        }

        if (isset($value['value']) && QuantityValidator::validate($value['value']) === false) {
            return false;
        }

        if (isset($value['data']) && HexValidator::validate($value['data']) === false) {
            return false;
        }

        if (isset($value['nonce']) && QuantityValidator::validate($value['nonce']) === false) {
            return false;
        }

        return true;
    }
}
