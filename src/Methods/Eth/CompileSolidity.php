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
use Web3\Validators\StringValidator;
use Web3\Formatters\StringFormatter;

class CompileSolidity extends EthMethod
{
    protected array $validators = [
        StringValidator::class,
    ];

    protected array $inputFormatters = [
        StringFormatter::class,
    ];

    public function getMethod(): string
    {
        return 'eth_compileSolidity';
    }
}
