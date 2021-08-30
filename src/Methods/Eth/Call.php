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
use Web3\Validators\CallValidator;
use Web3\Formatters\TransactionFormatter;
use Web3\Formatters\OptionalQuantityFormatter;

class Call extends EthMethod
{
    protected array $validators = [
        CallValidator::class,
        [
            TagValidator::class,
            QuantityValidator::class,
        ],
    ];

    protected array $inputFormatters = [
        TransactionFormatter::class,
        OptionalQuantityFormatter::class,
    ];

    protected array $defaultValues = [
        1 => 'latest',
    ];

    public function getMethod(): string
    {
        return 'eth_call';
    }
}
