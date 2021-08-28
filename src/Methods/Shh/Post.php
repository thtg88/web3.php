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
use Web3\Formatters\PostFormatter;
use Web3\Validators\PostValidator;

class Post extends EthMethod
{
    protected array $validators = [
        PostValidator::class,
    ];

    protected array $inputFormatters = [
        PostFormatter::class,
    ];
}
