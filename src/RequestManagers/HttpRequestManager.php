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
    /**
     * client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * construct
     *
     * @param string $host
     * @param int $timeout
     * @return void
     */
    public function __construct($host, $timeout = 1)
    {
        parent::__construct($host, $timeout);

        $this->client = new Client();
    }

    /**
     * sendPayload
     *
     * @param string $payload
     * @param callable $callback
     * @return void
     */
    public function sendPayload($payload, $callback)
    {
        if (!is_string($payload)) {
            throw new \InvalidArgumentException('Payload must be string.');
        }

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
            call_user_func($callback, $err, null);

            return;
        }

        /** @var StreamInterface $stream */
        $stream = $res->getBody();

        /** @var object $json */
        $json = json_decode($stream);

        $stream->close();

        if (JSON_ERROR_NONE !== json_last_error()) {
            call_user_func($callback, new InvalidArgumentException('json_decode error: ' . json_last_error_msg()), null);
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
                call_user_func($callback, $errors, $results);

                return;
            }

            call_user_func($callback, null, $results);

            return;
        }

        if (property_exists($json, 'result')) {
            call_user_func($callback, null, $json->result);

            return;
        }

        if (isset($json->error)) {
            $error = $json->error;

            call_user_func($callback, new RPCException(mb_ereg_replace('Error: ', '', $error->message), $error->code), null);

            return;
        }

        call_user_func($callback, new RPCException('Something wrong happened.'), null);
    }
}
