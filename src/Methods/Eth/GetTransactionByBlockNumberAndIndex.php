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
use Web3\Formatters\OptionalQuantityFormatter;
use Web3\Formatters\QuantityFormatter;

class GetTransactionByBlockNumberAndIndex extends EthMethod
{
    protected array $validators = [
        [
            TagValidator::class,
            QuantityValidator::class,
        ],
        QuantityValidator::class,
    ];

    protected array $inputFormatters = [
        OptionalQuantityFormatter::class,
        QuantityFormatter::class,
    ];

    public function getMethod(): string
    {
        return 'eth_getTransactionByBlockNumberAndIndex';
    }
}
