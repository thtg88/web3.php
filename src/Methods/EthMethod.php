<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods;

use InvalidArgumentException;
use RuntimeException;

abstract class EthMethod extends JSONRPC implements IMethod
{
    protected array $validators = [];
    protected array $inputFormatters = [];
    protected array $outputFormatters = [];
    protected array $defaultValues = [];

    public function getInputFormatters(): array
    {
        return $this->inputFormatters;
    }

    public function getOutputFormatters(): array
    {
        return $this->outputFormatters;
    }

    public function validate(array &$data): bool
    {
        $rules = $this->validators;

        if (count($data) < count($rules)) {
            if (!isset($this->defaultValues) || empty($this->defaultValues)) {
                throw new InvalidArgumentException('The params are less than validators.');
            }

            $defaultValues = $this->defaultValues;

            foreach ($defaultValues as $key => $value) {
                if (!isset($data[$key])) {
                    $data[$key] = $value;
                }
            }
        } elseif (count($data) > count($rules)) {
            throw new InvalidArgumentException('The params are more than validators.');
        }

        foreach ($rules as $key => $rule) {
            if (!isset($data[$key])) {
                throw new RuntimeException($this->method . ' method argument ' . $key . ' doesn\'t have default value.');
            }

            if (!is_array($rule)) {
                if (call_user_func([$rule, 'validate'], $data[$key]) === false) {
                    throw new RuntimeException('Wrong type of ' . $this->method . ' method argument ' . $key . '.');
                }

                continue;
            }

            $isError = true;

            foreach ($rule as $subRule) {
                if (call_user_func([$subRule, 'validate'], $data[$key]) === true) {
                    $isError = false;

                    break;
                }
            }
            if ($isError) {
                throw new RuntimeException('Wrong type of ' . $this->method . ' method argument ' . $key . '.');
            }
        }

        return true;
    }

    public function transform(array $data, array $rules): array
    {
        foreach ($data as $key => $param) {
            if (isset($rules[$key])) {
                $formatted = call_user_func([$rules[$key], 'format'], $param);
                $data[$key] = $formatted;
            }
        }

        return $data;
    }
}
