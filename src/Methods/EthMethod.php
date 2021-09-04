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

    public function validate(): bool
    {
        if (count($this->arguments) > count($this->validators)) {
            throw new InvalidArgumentException('The params are more than validators.');
        }

        if (count($this->arguments) < count($this->validators)) {
            if (!isset($this->defaultValues) || empty($this->defaultValues)) {
                throw new InvalidArgumentException('The params are less than validators.');
            }

            foreach ($this->defaultValues as $key => $value) {
                if (!isset($this->arguments[$key])) {
                    $this->arguments[$key] = $value;
                }
            }
        }

        foreach ($this->validators as $key => $rule) {
            if (!isset($this->arguments[$key])) {
                throw new RuntimeException($this->getMethod() . " method argument {$key} doesn't have default value.");
            }

            if (!is_array($rule)) {
                if (call_user_func([$rule, 'validate'], $this->arguments[$key]) === false) {
                    throw new RuntimeException('Wrong type of ' . $this->getMethod() . " method argument {$key}.");
                }

                continue;
            }

            $isError = true;

            foreach ($rule as $subRule) {
                if (call_user_func([$subRule, 'validate'], $this->arguments[$key]) === true) {
                    $isError = false;

                    break;
                }
            }
            if ($isError) {
                throw new RuntimeException('Wrong type of ' . $this->getMethod() . " method argument {$key}.");
            }
        }

        return true;
    }

    public function transform(array $rules): array
    {
        foreach ($this->arguments as $key => $param) {
            if (isset($rules[$key])) {
                $formatted = call_user_func([$rules[$key], 'format'], $param);

                $this->arguments[$key] = $formatted;
            }
        }

        return $this->arguments;
    }
}
