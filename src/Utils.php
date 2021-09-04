<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3;

use InvalidArgumentException;
use stdClass;
use kornrunner\Keccak;
use phpseclib\Math\BigInteger;

class Utils
{
    public const SHA3_NULL_HASH = 'c5d2460186f7233c927e7db2dcc703c0e500b653ca82273b7bfad8045d85a470';

    /**
     * UNITS
     * from ethjs-unit
     */
    public const UNITS = [
        'noether' => '0',
        'wei' => '1',
        'kwei' => '1000',
        'Kwei' => '1000',
        'babbage' => '1000',
        'femtoether' => '1000',
        'mwei' => '1000000',
        'Mwei' => '1000000',
        'lovelace' => '1000000',
        'picoether' => '1000000',
        'gwei' => '1000000000',
        'Gwei' => '1000000000',
        'shannon' => '1000000000',
        'nanoether' => '1000000000',
        'nano' => '1000000000',
        'szabo' => '1000000000000',
        'microether' => '1000000000000',
        'micro' => '1000000000000',
        'finney' => '1000000000000000',
        'milliether' => '1000000000000000',
        'milli' => '1000000000000000',
        'ether' => '1000000000000000000',
        'kether' => '1000000000000000000000',
        'grand' => '1000000000000000000000',
        'mether' => '1000000000000000000000000',
        'gether' => '1000000000000000000000000000',
        'tether' => '1000000000000000000000000000000',
    ];

    /**
     * NEGATIVE1
     * Cannot work, see: http://php.net/manual/en/language.constants.syntax.php
     *
     * @const
     */
    // const NEGATIVE1 = new BigInteger(-1);

    /**
     * Encoding string or integer or numeric string(is not zero prefixed) or big number to hex.
     *
     * @param string|int|BigInteger $value
     */
    public static function toHex($value, bool $isPrefix = false): string
    {
        // turn to hex number
        if (is_numeric($value)) {
            $bn = self::toBn($value);
            $hex = $bn->toHex(true);

            $hex = preg_replace('/^0+(?!$)/', '', $hex);

            return $isPrefix ? '0x' . $hex : $hex;
        }

        if (is_string($value)) {
            $value = self::stripZero($value);

            $hex = implode('', unpack('H*', $value));

            return $isPrefix ? '0x' . $hex : $hex;
        }

        if ($value instanceof BigInteger) {
            $hex = $value->toHex(true);

            $hex = preg_replace('/^0+(?!$)/', '', $hex);

            return $isPrefix ? '0x' . $hex : $hex;
        }

        throw new InvalidArgumentException('The value to toHex function is not support.');
    }

    public static function hexToBin(string $value): string
    {
        if (self::isZeroPrefixed($value)) {
            $count = 1;
            $value = str_replace('0x', '', $value, $count);
        }

        return pack('H*', $value);
    }

    public static function isZeroPrefixed(string $value): bool
    {
        return strpos($value, '0x') === 0;
    }

    public static function stripZero(string $value): string
    {
        if (!self::isZeroPrefixed($value)) {
            return $value;
        }

        $count = 1;

        return str_replace('0x', '', $value, $count);
    }

    public static function isNegative(string $value): bool
    {
        return strpos($value, '-') === 0;
    }

    public static function isAddress(string $value): bool
    {
        if (preg_match('/^(0x|0X)?[a-f0-9A-F]{40}$/', $value) !== 1) {
            return false;
        }

        if (
            preg_match('/^(0x|0X)?[a-f0-9]{40}$/', $value) === 1 ||
            preg_match('/^(0x|0X)?[A-F0-9]{40}$/', $value) === 1
        ) {
            return true;
        }

        return self::isAddressChecksum($value);
    }

    public static function isAddressChecksum(string $value): bool
    {
        $value = self::stripZero($value);
        $hash = self::stripZero(self::sha3(mb_strtolower($value)));

        for ($i = 0; $i < 40; $i++) {
            if (
                (intval($hash[$i], 16) > 7 && mb_strtoupper($value[$i]) !== $value[$i]) ||
                (intval($hash[$i], 16) <= 7 && mb_strtolower($value[$i]) !== $value[$i])
            ) {
                return false;
            }
        }

        return true;
    }

    public static function toChecksumAddress(string $value): string
    {
        $value = self::stripZero(strtolower($value));
        $hash = self::stripZero(self::sha3($value));
        $ret = '0x';

        for ($i = 0; $i < 40; $i++) {
            if (intval($hash[$i], 16) >= 8) {
                $ret .= strtoupper($value[$i]);
            } else {
                $ret .= $value[$i];
            }
        }

        return $ret;
    }

    public static function isHex(string $value): bool
    {
        return is_string($value) && preg_match('/^(0x)?[a-f0-9]*$/', $value) === 1;
    }

    public static function sha3(string $value): ?string
    {
        if (strpos($value, '0x') === 0) {
            $value = self::hexToBin($value);
        }

        $hash = Keccak::hash($value, 256);

        if ($hash === self::SHA3_NULL_HASH) {
            return null;
        }

        return '0x' . $hash;
    }

    public static function toString($value): string
    {
        $value = (string) $value;

        return $value;
    }

    /**
     * Change number from unit to wei.
     * For example:
     * $wei = Utils::toWei('1', 'kwei');
     * $wei->toString(); // 1000
     *
     * @param BigInteger|string $number
     * @param string $unit
     * @return \phpseclib\Math\BigInteger
     */
    public static function toWei($number, $unit)
    {
        if (!is_string($number) && !($number instanceof BigInteger)) {
            throw new InvalidArgumentException('toWei number must be string or bignumber.');
        }

        $bn = self::toBn($number);

        if (!is_string($unit)) {
            throw new InvalidArgumentException('toWei unit must be string.');
        }

        if (!isset(self::UNITS[$unit])) {
            throw new InvalidArgumentException('toWei doesn\'t support ' . $unit . ' unit.');
        }

        $bnt = new BigInteger(self::UNITS[$unit]);

        if (!is_array($bn)) {
            return $bn->multiply($bnt);
        }

        // fraction number
        list($whole, $fraction, $fractionLength, $negative1) = $bn;

        if ($fractionLength > strlen(self::UNITS[$unit])) {
            throw new InvalidArgumentException('toWei fraction part is out of limit.');
        }
        $whole = $whole->multiply($bnt);

        // There is no pow function in phpseclib 2.0, only can see in dev-master
        // Maybe implement own biginteger in the future
        // See 2.0 BigInteger: https://github.com/phpseclib/phpseclib/blob/2.0/phpseclib/Math/BigInteger.php
        // See dev-master BigInteger: https://github.com/phpseclib/phpseclib/blob/master/phpseclib/Math/BigInteger.php#L700
        // $base = (new BigInteger(10))->pow(new BigInteger($fractionLength));

        // So we switch phpseclib special global param, change in the future
        /** @psalm-suppress UndefinedConstant */
        switch (MATH_BIGINTEGER_MODE) {
            case $whole::MODE_GMP:
                $powerBase = gmp_pow(gmp_init(10), (int) $fractionLength);

                break;
            case $whole::MODE_BCMATH:
                $powerBase = bcpow('10', (string) $fractionLength, 0);

                break;
            default:
                $powerBase = pow(10, (int) $fractionLength);

                break;
        }
        $base = new BigInteger($powerBase);
        $fraction = $fraction->multiply($bnt)->divide($base)[0];

        if ($negative1 !== false) {
            return $whole->add($fraction)->multiply($negative1);
        }

        return $whole->add($fraction);
    }

    /**
     * Change number from unit to ether.
     * For example:
     * list($bnq, $bnr) = Utils::toEther('1', 'kether');
     * $bnq->toString(); // 1000
     *
     * @param BigInteger|string|int $number
     * @param string $unit
     * @return array
     */
    public static function toEther($number, $unit)
    {
        $wei = self::toWei($number, $unit);
        $bnt = new BigInteger(self::UNITS['ether']);

        return $wei->divide($bnt);
    }

    /**
     * Change number from wei to unit.
     * For example:
     * list($bnq, $bnr) = Utils::fromWei('1000', 'kwei');
     * $bnq->toString(); // 1
     *
     * @param BigInteger|string|int $number
     * @param string $unit
     * @return \phpseclib\Math\BigInteger
     */
    public static function fromWei($number, $unit)
    {
        $bn = self::toBn($number);

        if (!is_string($unit)) {
            throw new InvalidArgumentException('fromWei unit must be string.');
        }

        if (!isset(self::UNITS[$unit])) {
            throw new InvalidArgumentException('fromWei doesn\'t support ' . $unit . ' unit.');
        }

        $bnt = new BigInteger(self::UNITS[$unit]);

        return $bn->divide($bnt);
    }

    /**
     * @param stdClass|array $json
     */
    public static function jsonMethodToString(stdClass|array $json): string
    {
        if ($json instanceof stdClass) {
            // one way to change whole json stdClass to array type
            // $jsonString = json_encode($json);

            // if (JSON_ERROR_NONE !== json_last_error()) {
            //     throw new InvalidArgumentException('json_decode error: ' . json_last_error_msg());
            // }
            // $json = json_decode($jsonString, true);

            // another way to change whole json to array type but need the depth
            // $json = self::jsonToArray($json, $depth)

            // another way to change json to array type but not whole json stdClass
            $json = (array) $json;
            $typeName = [];

            foreach ($json['inputs'] as $param) {
                if (isset($param->type)) {
                    $typeName[] = $param->type;
                }
            }

            return $json['name'] . '(' . implode(',', $typeName) . ')';
        }

        if (isset($json['name']) && strpos($json['name'], '(') > 0) {
            return $json['name'];
        }

        $typeName = [];

        foreach ($json['inputs'] as $param) {
            if (isset($param['type'])) {
                $typeName[] = $param['type'];
            }
        }

        return $json['name'] . '(' . implode(',', $typeName) . ')';
    }

    public static function jsonToArray(stdClass|array $json): array
    {
        if ($json instanceof stdClass) {
            $json = (array) $json;

            foreach ($json as $key => $param) {
                if (is_array($param)) {
                    foreach ($param as $subKey => $subParam) {
                        $json[$key][$subKey] = self::jsonToArray($subParam);
                    }
                } elseif ($param instanceof stdClass) {
                    $json[$key] = self::jsonToArray($param);
                }
            }

            return $json;
        }

        if (is_array($json)) {
            foreach ($json as $key => $param) {
                if (is_array($param)) {
                    foreach ($param as $subKey => $subParam) {
                        $json[$key][$subKey] = self::jsonToArray($subParam);
                    }
                } elseif ($param instanceof stdClass) {
                    $json[$key] = self::jsonToArray($param);
                }
            }
        }

        return $json;
    }

    /**
     * Change number or number string to bignumber.
     */
    public static function toBn(BigInteger|string|int $number): BigInteger
    {
        if ($number instanceof BigInteger) {
            return $number;
        }

        if (is_int($number)) {
            return new BigInteger($number);
        }

        if (is_numeric($number)) {
            $number = (string) $number;

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigInteger(-1);
            }
            if (strpos($number, '.') > 0) {
                $comps = explode('.', $number);

                if (count($comps) > 2) {
                    throw new InvalidArgumentException('toBn number must be a valid number.');
                }
                $whole = $comps[0];
                $fraction = $comps[1];

                return [
                    new BigInteger($whole),
                    new BigInteger($fraction),
                    strlen($comps[1]),
                    isset($negative1) ? $negative1 : false,
                ];
            } else {
                $bn = new BigInteger($number);
            }
            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }

            return $bn;
        }

        $number = mb_strtolower($number);

        if (self::isNegative($number)) {
            $count = 1;
            $number = str_replace('-', '', $number, $count);
            $negative1 = new BigInteger(-1);
        }
        if (self::isZeroPrefixed($number) || preg_match('/[a-f]+/', $number) === 1) {
            $number = self::stripZero($number);
            $bn = new BigInteger($number, 16);
        } elseif (empty($number)) {
            $bn = new BigInteger(0);
        } else {
            throw new InvalidArgumentException('toBn number must be valid hex string.');
        }
        if (isset($negative1)) {
            $bn = $bn->multiply($negative1);
        }

        return $bn;
    }
}
