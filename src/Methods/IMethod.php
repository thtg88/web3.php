<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Methods;

interface IMethod
{
    public function transform(array $arguments, array $rules): array;
    public function validate(): bool;
    public function toPayloadString(): string;
}
