<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Contracts;

use InvalidArgumentException;
use Web3\Contracts\Types\IType;
use Web3\Formatters\IntegerFormatter;
use Web3\Utils;

abstract class SolidityType implements IType
{
    /**
     * @param string $name
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }

        return false;
    }

    /**
     * @param string $name
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$value]);
        }

        return false;
    }

    public function nestedTypes(string $name)
    {
        $matches = [];

        if (preg_match_all('/(\[[0-9]*\])/', $name, $matches, PREG_PATTERN_ORDER) >= 1) {
            return $matches[0];
        }

        return false;
    }

    public function nestedName(string $name): string
    {
        $nestedTypes = $this->nestedTypes($name);

        if ($nestedTypes === false) {
            return $name;
        }

        return mb_substr($name, 0, mb_strlen($name) - mb_strlen($nestedTypes[count($nestedTypes) - 1]));
    }

    public function isDynamicArray(string $name): bool
    {
        $nestedTypes = $this->nestedTypes($name);

        return $nestedTypes && preg_match('/[0-9]{1,}/', $nestedTypes[count($nestedTypes) - 1]) !== 1;
    }

    public function isStaticArray(string $name): bool
    {
        $nestedTypes = $this->nestedTypes($name);

        return $nestedTypes && preg_match('/[0-9]{1,}/', $nestedTypes[count($nestedTypes) - 1]) === 1;
    }

    public function staticArrayLength(string $name): int
    {
        $nestedTypes = $this->nestedTypes($name);

        if ($nestedTypes === false) {
            return 1;
        }

        $match = [];

        if (preg_match('/[0-9]{1,}/', $nestedTypes[count($nestedTypes) - 1], $match) === 1) {
            return (int) $match[0];
        }

        return 1;
    }

    public function staticPartLength(string $name): int
    {
        $nestedTypes = $this->nestedTypes($name);

        if ($nestedTypes === false) {
            $nestedTypes = ['[1]'];
        }

        $count = 32;

        foreach ($nestedTypes as $type) {
            $num = mb_substr($type, 1, 1);

            if (!is_numeric($num)) {
                $num = 1;
            } else {
                $num = intval($num);
            }

            $count *= $num;
        }

        return $count;
    }

    public function isDynamicType(): bool
    {
        return false;
    }

    public function encode(array $value, string $name): string|array
    {
        if ($this->isDynamicArray($name)) {
            $length = count($value);
            $nestedName = $this->nestedName($name);
            $result = [];
            $result[] = IntegerFormatter::format($length);

            foreach ($value as $val) {
                $result[] = $this->encode($val, $nestedName);
            }

            return $result;
        }

        if ($this->isStaticArray($name)) {
            $length = $this->staticArrayLength($name);
            $nestedName = $this->nestedName($name);
            $result = [];

            foreach ($value as $val) {
                $result[] = $this->encode($val, $nestedName);
            }

            return $result;
        }

        return $this->inputFormat($value, $name);
    }

    public function decode(string $value, int $offset, string $name): array|string
    {
        if ($this->isDynamicArray($name)) {
            $arrayOffset = (int) Utils::toBn('0x' . mb_substr($value, $offset * 2, 64))->toString();
            $length = (int) Utils::toBn('0x' . mb_substr($value, $arrayOffset * 2, 64))->toString();
            $arrayStart = $arrayOffset + 32;

            $nestedName = $this->nestedName($name);
            $nestedStaticPartLength = $this->staticPartLength($nestedName);
            $roundedNestedStaticPartLength = floor(($nestedStaticPartLength + 31) / 32) * 32;
            $result = [];

            for ($i = 0; $i < $length * $roundedNestedStaticPartLength; $i += $roundedNestedStaticPartLength) {
                $result[] = $this->decode($value, $arrayStart + $i, $nestedName);
            }

            return $result;
        }

        if ($this->isStaticArray($name)) {
            $length = $this->staticArrayLength($name);
            $arrayStart = $offset;

            $nestedName = $this->nestedName($name);
            $nestedStaticPartLength = $this->staticPartLength($nestedName);
            $roundedNestedStaticPartLength = floor(($nestedStaticPartLength + 31) / 32) * 32;
            $result = [];

            for ($i = 0; $i < $length * $roundedNestedStaticPartLength; $i += $roundedNestedStaticPartLength) {
                $result[] = $this->decode($value, $arrayStart + $i, $nestedName);
            }

            return $result;
        }

        if ($this->isDynamicType()) {
            $dynamicOffset = (int) Utils::toBn('0x' . mb_substr($value, $offset * 2, 64))->toString();
            $length = (int) Utils::toBn('0x' . mb_substr($value, $dynamicOffset * 2, 64))->toString();
            $roundedLength = floor(($length + 31) / 32);
            $param = mb_substr($value, $dynamicOffset * 2, (1 + $roundedLength) * 64);

            return $this->outputFormat($param, $name);
        }

        $length = $this->staticPartLength($name);
        $param = mb_substr($value, $offset * 2, $length * 2);

        return $this->outputFormat($param, $name);
    }

    abstract public function inputFormat($value, $name): string;
    abstract public function outputFormat($value, $name): string;
}
