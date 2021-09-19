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
use stdClass;
use Web3\Utils;
use Web3\Formatters\IntegerFormatter;

class Ethabi
{
    protected array $types = [];

    public function __construct(array $types = [])
    {
        $this->types = $types;
    }

    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);

        return $this->$method();
    }

    public function __set(string $name, mixed $value)
    {
        $method = 'set' . ucfirst($name);

        return $this->$method($value);
    }

    public function encodeFunctionSignature(string|stdClass|array $functionName): string
    {
        if (!is_string($functionName)) {
            $functionName = Utils::jsonMethodToString($functionName);
        }

        return mb_substr(Utils::sha3($functionName), 0, 10);
    }

    /**
     * @todo Fix same event name with different params
     */
    public function encodeEventSignature(string|stdClass|array $functionName): string
    {
        if (!is_string($functionName)) {
            $functionName = Utils::jsonMethodToString($functionName);
        }

        return Utils::sha3($functionName);
    }

    public function encodeParameter(string $type, $param): string
    {
        return $this->encodeParameters([$type], [$param]);
    }

    public function encodeParameters(stdClass|array $types, array $params): string
    {
        // change json to array
        if ($types instanceof stdClass && isset($types->inputs)) {
            $types = Utils::jsonToArray($types, 2);
        }

        if (is_array($types) && isset($types['inputs'])) {
            $inputTypes = $types;
            $types = [];

            foreach ($inputTypes['inputs'] as $input) {
                if (isset($input['type'])) {
                    $types[] = $input['type'];
                }
            }
        }
        if (count($types) !== count($params)) {
            throw new InvalidArgumentException('encodeParameters number of types must equal to number of params.');
        }

        $typesLength = count($types);
        $solidityTypes = $this->getSolidityTypes($types);
        $encodes = array_fill(0, $typesLength, '');

        foreach ($solidityTypes as $key => $type) {
            $encodes[$key] = call_user_func([$type, 'encode'], $params[$key], $types[$key]);
        }

        $dynamicOffset = 0;

        foreach ($solidityTypes as $key => $type) {
            $staticPartLength = $type->staticPartLength($types[$key]);
            $roundedStaticPartLength = floor(($staticPartLength + 31) / 32) * 32;

            if ($type->isDynamicType($types[$key]) || $type->isDynamicArray($types[$key])) {
                $dynamicOffset += 32;
            } else {
                $dynamicOffset += $roundedStaticPartLength;
            }
        }

        return '0x' . $this->encodeMultiWithOffset($types, $solidityTypes, $encodes, $dynamicOffset);
    }

    public function decodeParameter(string $type, string $param): string
    {
        return $this->decodeParameters([$type], $param)[0];
    }

    public function decodeParameters(stdClass|array $types, string $param): array
    {
        // change json to array
        if ($types instanceof stdClass && isset($types->outputs)) {
            $types = Utils::jsonToArray($types, 2);
        }

        if (is_array($types) && isset($types['outputs'])) {
            $outputTypes = $types;
            $types = [];

            foreach ($outputTypes['outputs'] as $output) {
                if (isset($output['type'])) {
                    $types[] = $output['type'];
                }
            }
        }

        $typesLength = count($types);
        $solidityTypes = $this->getSolidityTypes($types);
        $offsets = array_fill(0, $typesLength, 0);

        for ($i = 0; $i < $typesLength; $i++) {
            $offsets[$i] = $solidityTypes[$i]->staticPartLength($types[$i]);
        }
        for ($i=1; $i<$typesLength; $i++) {
            $offsets[$i] += $offsets[$i - 1];
        }
        for ($i = 0; $i < $typesLength; $i++) {
            $offsets[$i] -= $solidityTypes[$i]->staticPartLength($types[$i]);
        }

        $result = [];
        $param = mb_strtolower(Utils::stripZero($param));

        for ($i = 0; $i < $typesLength; $i++) {
            if (isset($outputTypes['outputs'][$i]['name']) && empty($outputTypes['outputs'][$i]['name']) === false) {
                $result[$outputTypes['outputs'][$i]['name']] = $solidityTypes[$i]->decode($param, $offsets[$i], $types[$i]);
            } else {
                $result[$i] = $solidityTypes[$i]->decode($param, $offsets[$i], $types[$i]);
            }
        }

        return $result;
    }

    protected function getSolidityTypes(array $types): array
    {
        $solidityTypes = array_fill(0, count($types), 0);

        foreach ($types as $key => $type) {
            $match = [];

            if (preg_match('/^([a-zA-Z]+)/', $type, $match) !== 1) {
                continue;
            }

            if (!isset($this->types[$match[0]])) {
                continue;
            }

            $className = $this->types[$match[0]];

            // check dynamic bytes
            if (call_user_func([$this->types[$match[0]], 'isType'], $type) === false) {
                if ($match[0] !== 'bytes') {
                    throw new InvalidArgumentException('Unsupport solidity parameter type: ' . $type);
                }

                $className = $this->types['dynamicBytes'];
            }

            $solidityTypes[$key] = $className;
        }

        return $solidityTypes;
    }

    protected function encodeWithOffset(string $type, SolidityType $solidityType, array|string $encoded, int $offset): string|array
    {
        if ($solidityType->isDynamicArray($type)) {
            $nestedName = $solidityType->nestedName($type);
            $nestedStaticPartLength = $solidityType->staticPartLength($type);
            $result = $encoded[0];

            if ($solidityType->isDynamicArray($nestedName)) {
                $previousLength = 2;

                for ($i = 0; $i < count($encoded); $i++) {
                    if (isset($encoded[$i - 1])) {
                        $previousLength += abs($encoded[$i - 1][0]);
                    }
                    $result .= IntegerFormatter::format($offset + $i * $nestedStaticPartLength + $previousLength * 32);
                }
            }
            for ($i = 0; $i < count($encoded); $i++) {
                // $bn = Utils::toBn($result);
                // $divided = $bn->divide(Utils::toBn(2));

                // if (is_array($divided)) {
                //     $additionalOffset = (int) $divided[0]->toString();
                // } else {
                //     $additionalOffset = 0;
                // }
                $additionalOffset = floor(mb_strlen($result) / 2);
                $result .= $this->encodeWithOffset($nestedName, $solidityType, $encoded[$i], $offset + $additionalOffset);
            }

            return mb_substr($result, 64);
        }

        if (!$solidityType->isStaticArray($type)) {
            return $encoded;
        }

        $nestedName = $solidityType->nestedName($type);
        $nestedStaticPartLength = $solidityType->staticPartLength($type);
        $result = '';

        if ($solidityType->isDynamicArray($nestedName)) {
            $previousLength = 0;

            for ($i = 0; $i < count($encoded); $i++) {
                if (isset($encoded[$i - 1])) {
                    $previousLength += abs($encoded[$i - 1])[0];
                }
                $result .= IntegerFormatter::format($offset + $i * $nestedStaticPartLength + $previousLength * 32);
            }
        }

        for ($i = 0; $i < count($encoded); $i++) {
            // $bn = Utils::toBn($result);
            // $divided = $bn->divide(Utils::toBn(2));

            // if (is_array($divided)) {
            //     $additionalOffset = (int) $divided[0]->toString();
            // } else {
            //     $additionalOffset = 0;
            // }
            $additionalOffset = floor(mb_strlen($result) / 2);
            $result .= $this->encodeWithOffset($nestedName, $solidityType, $encoded[$i], $offset + $additionalOffset);
        }

        return $result;
    }

    /**
     * @param int $dynamicOffset
     */
    protected function encodeMultiWithOffset(array $types, array $solidityTypes, array $encodes, $dynamicOffset): string
    {
        $result = '';

        foreach ($solidityTypes as $key => $type) {
            if ($type->isDynamicType($types[$key]) || $type->isDynamicArray($types[$key])) {
                $result .= IntegerFormatter::format($dynamicOffset);
                $e = $this->encodeWithOffset($types[$key], $type, $encodes[$key], $dynamicOffset);
                $dynamicOffset += floor(mb_strlen($e) / 2);
            } else {
                $result .= $this->encodeWithOffset($types[$key], $type, $encodes[$key], $dynamicOffset);
            }
        }
        foreach ($solidityTypes as $key => $type) {
            if ($type->isDynamicType($types[$key]) || $type->isDynamicArray($types[$key])) {
                $e = $this->encodeWithOffset($types[$key], $type, $encodes[$key], $dynamicOffset);
                // $dynamicOffset += floor(mb_strlen($e) / 2);
                $result .= $e;
            }
        }

        return $result;
    }
}
