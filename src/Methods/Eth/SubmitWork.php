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
use Web3\Validators\NonceValidator;
use Web3\Validators\BlockHashValidator;
use Web3\Formatters\QuantityFormatter;

class SubmitWork extends EthMethod
{
    protected array $validators = [
        NonceValidator::class,
        BlockHashValidator::class,
        BlockHashValidator::class,
    ];

    protected array $inputFormatters = [
        QuantityFormatter::class,
    ];

    public function getMethod(): string
    {
        return 'eth_submitWork';
    }
}
