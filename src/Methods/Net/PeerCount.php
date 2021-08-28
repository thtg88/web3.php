<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods\Net;

use Web3\Methods\EthMethod;
use Web3\Formatters\BigNumberFormatter;

class PeerCount extends EthMethod
{
    protected array $outputFormatters = [
        BigNumberFormatter::class,
    ];
}
