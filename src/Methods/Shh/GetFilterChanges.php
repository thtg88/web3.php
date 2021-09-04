<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods\Shh;

use Web3\Methods\EthMethod;
use Web3\Validators\QuantityValidator;
use Web3\Formatters\QuantityFormatter;

class GetFilterChanges extends EthMethod
{
    protected array $validators = [
        QuantityValidator::class,
    ];

    protected array $inputFormatters = [
        QuantityFormatter::class,
    ];

    public function getMethod(): string
    {
        return 'shh_getFilterChanges';
    }
}
