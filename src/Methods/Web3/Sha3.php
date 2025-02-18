<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods\Web3;

use Web3\Methods\EthMethod;
use Web3\Formatters\HexFormatter;
use Web3\Validators\StringValidator;

class Sha3 extends EthMethod
{
    protected array $validators = [
        StringValidator::class,
    ];

    protected array $inputFormatters = [
        HexFormatter::class,
    ];

    public function getMethod(): string
    {
        return 'web3_sha3';
    }
}
