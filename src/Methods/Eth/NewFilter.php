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
use Web3\Validators\FilterValidator;

class NewFilter extends EthMethod
{
    protected array $validators = [
        FilterValidator::class,
    ];

    public function getMethod(): string
    {
        return 'eth_newFilter';
    }
}
