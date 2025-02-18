<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods\Personal;

use Web3\Methods\EthMethod;
use Web3\Validators\AddressValidator;
use Web3\Validators\StringValidator;
use Web3\Validators\QuantityValidator;
use Web3\Formatters\AddressFormatter;
use Web3\Formatters\StringFormatter;
use Web3\Formatters\NumberFormatter;

class UnlockAccount extends EthMethod
{
    protected array $validators = [
        AddressValidator::class,
        StringValidator::class,
        QuantityValidator::class,
    ];

    protected array $inputFormatters = [
        AddressFormatter::class,
        StringFormatter::class,
        NumberFormatter::class,
    ];

    protected array $defaultValues = [
        2 => 300,
    ];

    public function getMethod(): string
    {
        return 'personal_unlockAccount';
    }
}
