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

class EthMethod extends JSONRPC implements IMethod
{
    /**
     * validators
     *
     * @var array
     */
    protected $validators = [];

    /**
     * inputFormatters
     *
     * @var array
     */
    protected $inputFormatters = [];

    /**
     * outputFormatters
     *
     * @var array
     */
    protected $outputFormatters = [];

    /**
     * defaultValues
     *
     * @var array
     */
    protected $defaultValues = [];

    /**
     * construct
     *
     * @return void
     */
    // public function __construct($method='', $arguments=[])
    // {
    //     parent::__construct($method, $arguments);
    // }

    /**
     * getInputFormatters
     *
     * @return array
     */
    public function getInputFormatters()
    {
        return $this->inputFormatters;
    }

    /**
     * getOutputFormatters
     *
     * @return array
     */
    public function getOutputFormatters()
    {
        return $this->outputFormatters;
    }

    /**
     * validate
     *
     * @param array $data
     * @return bool
     */
    public function validate(&$data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Please use array params when call validate.');
        }
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
            if (isset($data[$key])) {
                if (is_array($rule)) {
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
                } else {
                    if (call_user_func([$rule, 'validate'], $data[$key]) === false) {
                        throw new RuntimeException('Wrong type of ' . $this->method . ' method argument ' . $key . '.');
                    }
                }
            } else {
                throw new RuntimeException($this->method . ' method argument ' . $key . ' doesn\'t have default value.');
            }
        }

        return true;
    }

    /**
     * transform
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    public function transform($data, $rules)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Please use array params when call transform.');
        }
        if (!is_array($rules)) {
            throw new InvalidArgumentException('Please use array rules when call transform.');
        }
        foreach ($data as $key => $param) {
            if (isset($rules[$key])) {
                $formatted = call_user_func([$rules[$key], 'format'], $param);
                $data[$key] = $formatted;
            }
        }

        return $data;
    }
}
