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
use Web3\Formatters\AddressFormatter;

class LockAccount extends EthMethod
{
    protected array $validators = [
        AddressValidator::class,
    ];

    protected array $inputFormatters = [
        AddressFormatter::class,
    ];

    public function getMethod(): string
    {
        return 'personal_lockAccount';
    }
}
