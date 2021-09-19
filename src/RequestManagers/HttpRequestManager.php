<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3\RequestManagers;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException as RPCException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class HttpRequestManager extends RequestManager implements IRequestManager
{
    protected Client $client;

    public function __construct(string $host, float $timeout = 1)
    {
        parent::__construct($host, $timeout);

        $this->client = new Client();
    }

    public function sendPayload(string $payload): array
    {
        try {
            $res = $this->client->post($this->host, [
                'headers' => [
                    'content-type' => 'application/json',
                ],
                'body' => $payload,
                'timeout' => $this->timeout,
                'connect_timeout' => $this->timeout,
            ]);
        } catch (RequestException $err) {
            return [$err, null];
        }

        /** @var StreamInterface $stream */
        $stream = $res->getBody();

        /** @var object $json */
        $json = json_decode($stream);

        $stream->close();

        if (JSON_ERROR_NONE !== json_last_error()) {
            $error = new InvalidArgumentException('json_decode error: ' . json_last_error_msg());

            return [$error, null];
        }

        if (is_array($json)) {
            // batch results
            $results = [];
            $errors = [];

            foreach ($json as $result) {
                if (property_exists($result, 'result')) {
                    $results[] = $result->result;

                    continue;
                }

                if (isset($json->error)) {
                    $error = $json->error;
                    $errors[] = new RPCException(mb_ereg_replace('Error: ', '', $error->message), $error->code);

                    continue;
                }

                $results[] = null;
            }

            if (count($errors) > 0) {
                return [$errors, $results];
            }

            return [null, $results];
        }

        if (property_exists($json, 'result')) {
            return [null, $json->result];
        }

        if (isset($json->error)) {
            $error = new RPCException(mb_ereg_replace('Error: ', '', $json->error->message), $json->error->code);

            return [$error, null];
        }

        $error = new RPCException('Something wrong happened.');

        return [$error, null];
    }
}
