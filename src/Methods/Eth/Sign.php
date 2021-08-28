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
use Web3\Validators\AddressValidator;
use Web3\Validators\HexValidator;
use Web3\Formatters\AddressFormatter;
use Web3\Formatters\HexFormatter;

class Sign extends EthMethod
{
    protected array $validators = [
        AddressValidator::class, HexValidator::class,
    ];

    protected array $inputFormatters = [
        AddressFormatter::class, HexFormatter::class,
    ];
}
