<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods\Eth;

use Web3\Methods\EthMethod;
use Web3\Validators\TagValidator;
use Web3\Validators\QuantityValidator;
use Web3\Validators\AddressValidator;
use Web3\Formatters\AddressFormatter;
use Web3\Formatters\OptionalQuantityFormatter;
use Web3\Formatters\BigNumberFormatter;

class GetBalance extends EthMethod
{
    protected array $validators = [
        AddressValidator::class,
        [
            TagValidator::class,
            QuantityValidator::class,
        ],
    ];

    protected array $inputFormatters = [
        AddressFormatter::class,
        OptionalQuantityFormatter::class,
    ];

    protected array $outputFormatters = [
        BigNumberFormatter::class,
    ];

    protected array $defaultValues = [
        1 => 'latest',
    ];

    public function getMethod(): string
    {
        return 'eth_getBalance';
    }
}
