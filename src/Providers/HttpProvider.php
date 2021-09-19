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

    public function send(IMethod $method, ?callable $callback = null): ?array
    {
        $payload = $method->toPayloadString();

        if ($this->isBatch) {
            $this->methods[] = $method;
            $this->batch[] = $payload;

            return null;
        }

        // TODO: should it throw?
        if (!$method->validate()) {
            return ['Validation failed', null];
        }

        $method->arguments = $method->transform(
            $method->arguments,
            $method->inputFormatters
        );

        [$err, $res] = $this->requestManager->sendPayload($payload, function () {
        });

        if ($err !== null) {
            $callback($err, null);

            return [$err, null];
        }

        if (!is_array($res)) {
            $res = $method->transform([$res], $method->outputFormatters);

            $callback(null, $res[0]);

            return [null, $res[0]];
        }

        $res = $method->transform($res, $method->outputFormatters);

        $callback(null, $res);

        return [null, $res];
    }

    /**
     * @param bool $status
     */
    public function batch($status): void
    {
        $status = is_bool($status);

        $this->isBatch = $status;
    }

    public function execute(callable $callback): void
    {
        if (!$this->isBatch) {
            throw new RuntimeException('Please batch json rpc first.');
        }

        $methods = $this->methods;
        $proxy = function ($err, $res) use ($methods, $callback) {
            if ($err !== null) {
                return $callback($err, null);
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

            return $callback(null, $res);
        };

        $this->requestManager->sendPayload('[' . implode(',', $this->batch) . ']', $proxy);

        $this->methods[] = [];
        $this->batch = [];
    }
}
