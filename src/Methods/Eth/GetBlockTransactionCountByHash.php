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
use Web3\Validators\BlockHashValidator;
use Web3\Formatters\HexFormatter;
use Web3\Formatters\BigNumberFormatter;

class GetBlockTransactionCountByHash extends EthMethod
{
    protected array $validators = [
        BlockHashValidator::class,
    ];

    protected array $inputFormatters = [
        HexFormatter::class,
    ];

    protected array $outputFormatters = [
        BignumberFormatter::class,
    ];
}
