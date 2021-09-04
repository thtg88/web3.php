<?php

namespace Web3\Tests\Unit;

use TypeError;
use Web3\Tests\TestCase;
use Web3\Methods\JSONRPC;

class JSONRPCTest extends TestCase
{
    /** @test */
    public function json_rpc(): void
    {
        $id = rand();
        $params = [
            'hello world',
        ];
        $rpc = new class($params) extends JSONRPC {
            public function getMethod(): string
            {
                return 'echo';
            }
        };
        $rpc->id = $id;

        $this->assertEquals($id, $rpc->id);
        $this->assertEquals('2.0', $rpc->rpcVersion);
        $this->assertEquals('echo', $rpc->getMethod());
        $this->assertEquals($params, $rpc->arguments);
        $this->assertEquals([
            'id' => $id,
            'jsonrpc' => '2.0',
            'method' => 'echo',
            'params' => $params,
        ], $rpc->toPayload());
        $this->assertEquals(json_encode($rpc->toPayload()), (string) $rpc);

        $params = [
            'hello ethereum',
        ];
        $rpc->arguments = $params;

        $this->assertEquals($params, $rpc->arguments);
        $this->assertEquals([
            'id' => $id,
            'jsonrpc' => '2.0',
            'method' => 'echo',
            'params' => $params,
        ], $rpc->toPayload());
        $this->assertEquals(json_encode($rpc->toPayload()), (string) $rpc);
    }

    /** @test */
    public function id_not_integer(): void
    {
        $id = 'zzz';
        $params = ['hello world'];
        $rpc = new class($params) extends JSONRPC {
            public function getMethod(): string
            {
                return 'echo';
            }
        };

        $this->expectException(TypeError::class);

        $rpc->id = $id;
    }

    /** @test */
    public function arguments_not_array(): void
    {
        $id = 'zzz';
        $params = ['hello world'];
        $rpc = new class($params) extends JSONRPC {
            public function getMethod(): string
            {
                return 'echo';
            }
        };

        $this->expectException(TypeError::class);

        $rpc->arguments = $id;
    }
}
