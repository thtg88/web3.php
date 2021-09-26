<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\Providers;

use RuntimeException;
use Web3\Methods\IMethod;

class HttpProvider extends Provider implements IProvider
{
    protected array $methods = [];

    public function send(IMethod $method): ?array
    {
        $method->validate();

        $payload = $method->toPayloadString();

        if ($this->isBatch) {
            $this->methods[] = $method;
            $this->batch[] = $payload;

            return null;
        }

        $method->arguments = $method->transform(
            $method->arguments,
            $method->inputFormatters
        );

        [$err, $res] = $this->requestManager->sendPayload($payload);

        if (!is_array($res)) {
            $res = $method->transform([$res], $method->outputFormatters);

            return [null, $res[0]];
        }

        $res = $method->transform($res, $method->outputFormatters);

        return [null, $res];
    }

    public function batch(bool $status = true): self
    {
        $this->isBatch = $status;

        return $this;
    }

    public function execute(): array
    {
        if (!$this->isBatch) {
            throw new RuntimeException('Please batch json rpc first.');
        }

        $methods = $this->methods;

        [$err, $res] = $this->requestManager->sendPayload('[' . implode(',', $this->batch) . ']');

        if ($err !== null) {
            $this->methods = [];
            $this->batch = [];

            return [$err, null];
        }

        foreach ($methods as $key => $method) {
            if (!isset($res[$key])) {
                continue;
            }

            if (!is_array($res[$key])) {
                $transformed = $method->transform([$res[$key]], $method->outputFormatters);
                $res[$key] = $transformed[0];
            } else {
                $transformed = $method->transform($res[$key], $method->outputFormatters);
                $res[$key] = $transformed;
            }
        }

        $this->methods = [];
        $this->batch = [];

        return [null, $res];
    }
}
