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

class ClientVersion extends EthMethod
{
    public function getMethod(): string
    {
        return 'web3_clientVersion';
    }
}
