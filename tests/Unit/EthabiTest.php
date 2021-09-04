<?php

namespace Web3\Tests\Unit;

use Web3\Tests\TestCase;
use Web3\Utils;
use Web3\Contracts\Ethabi;
use Web3\Contracts\Types\Address;
use Web3\Contracts\Types\Boolean;
use Web3\Contracts\Types\Bytes;
use Web3\Contracts\Types\DynamicBytes;
use Web3\Contracts\Types\Integer;
use Web3\Contracts\Types\Str;
use Web3\Contracts\Types\Uinteger;

class EthabiTest extends TestCase
{
    protected Ethabi $abi;

    /**
     * from GameToken approve function
     */
    protected string $testJsonMethodString;

    /**
     * from web3 abi.encodeParameter.js test
     * and web3 eth.abi.encodeParameters test
     * and web3 eth.abi.encodeParameter test
     */
    protected array $encodingTests = [
        [
            'params' => [['uint256','string'], ['2345675643', 'Hello!%']],
            'result' => '0x000000000000000000000000000000000000000000000000000000008bd02b7b0000000000000000000000000000000000000000000000000000000000000040000000000000000000000000000000000000000000000000000000000000000748656c6c6f212500000000000000000000000000000000000000000000000000',
        ],
        [
            'params' => [['uint8[]','bytes32'], [['34','434'], '0x324567dfff']],
            'result' => '0x0000000000000000000000000000000000000000000000000000000000000040324567dfff0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000002000000000000000000000000000000000000000000000000000000000000002200000000000000000000000000000000000000000000000000000000000001b2',
        ],
        [
            'params' => [['address','address','address', 'address'], ['0x90f8bf6a479f320ead074411a4b0e7944ea8c9c1','','0x0', null]],
            'result' => '0x00000000000000000000000090f8bf6a479f320ead074411a4b0e7944ea8c9c1000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000',
        ],
        [
            'params' => [['bool[2]', 'bool[3]'], [[true, false], [false, false, true]]],
            'result' => '0x00000000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001',
        ],
        [
            'params' => [['int'], [1]],
            'result' => '0x0000000000000000000000000000000000000000000000000000000000000001',
        ],
        [
            'params' => [['int'], [16]],
            'result' => '0x0000000000000000000000000000000000000000000000000000000000000010',
        ],
        [
            'params' => [['int'], [-1]],
            'result' => '0xffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
        ],
        [
            'params' => [['int256'], [1]],
            'result' => '0x0000000000000000000000000000000000000000000000000000000000000001',
        ],
        [
            'params' => [['int256'], [16]],
            'result' => '0x0000000000000000000000000000000000000000000000000000000000000010',
        ],
        [
            'params' => [['int256'], [-1]],
            'result' => '0xffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
        ],
        [
            'params' => [['int[]'], [[3]]],
            'result' => '0x000000000000000000000000000000000000000000000000000000000000002000000000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000003',
        ],
        [
            'params' => [['int256[]'], [[3]]],
            'result' => '0x000000000000000000000000000000000000000000000000000000000000002000000000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000003',
        ],
        [
            'params' => [['int256[]'], [[1,2,3]]],
            'result' => '0x00000000000000000000000000000000000000000000000000000000000000200000000000000000000000000000000000000000000000000000000000000003000000000000000000000000000000000000000000000000000000000000000100000000000000000000000000000000000000000000000000000000000000020000000000000000000000000000000000000000000000000000000000000003',
        ],
        [
            'params' => [['int[]','int[]'], [[1,2],[3,4]]],
            'result' => '0x000000000000000000000000000000000000000000000000000000000000004000000000000000000000000000000000000000000000000000000000000000a0000000000000000000000000000000000000000000000000000000000000000200000000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000002000000000000000000000000000000000000000000000000000000000000000200000000000000000000000000000000000000000000000000000000000000030000000000000000000000000000000000000000000000000000000000000004',
        ],
    ];

    /**
     * decodingTests
     * from web3 abi.decodeParameter.js test
     * and web3 eth.abi.decodeParameters test
     * and web3 eth.abi.decodeParameter test
     */
    protected array $decodingTests = [
        [
            'params' => [['uint256'], '0x0000000000000000000000000000000000000000000000000000000000000010'],
            'result' => ['16'],
        ],
        [
            'params' => [['string'], '0x0000000000000000000000000000000000000000000000000000000000000020000000000000000000000000000000000000000000000000000000000000000848656c6c6f212521000000000000000000000000000000000000000000000000'],
            'result' => ['Hello!%!'],
        ],
        [
            'params' => [['uint256','string'], '0x000000000000000000000000000000000000000000000000000000008bd02b7b0000000000000000000000000000000000000000000000000000000000000040000000000000000000000000000000000000000000000000000000000000000748656c6c6f212500000000000000000000000000000000000000000000000000'],
            'result' => ['2345675643', 'Hello!%'],
        ],
        [
            'params' => [['string'], '0x00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'],
            'result' => [''],
        ],
        [
            'params' => [['int256'], '0x0000000000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0'],
        ],
        [
            'params' => [['uint256'], '0x0000000000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0'],
        ],
        [
            'params' => [['address'], '0x0000000000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0x0000000000000000000000000000000000000000'],
        ],
        [
            'params' => [['bool'], '0x0000000000000000000000000000000000000000000000000000000000000000'],
            'result' => [false],
        ],
        [
            'params' => [['bytes'], '0x0000000000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0'],
        ],
        [
            'params' => [['bytes32'], '0x0000000000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0'],
        ],
        [
            'params' => [['bytes32'], '0xdf32340000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0xdf32340000000000000000000000000000000000000000000000000000000000'],
        ],
        [
            'params' => [['bytes32[]'], '0x00000000000000000000000000000000000000000000000000000000000000200000000000000000000000000000000000000000000000000000000000000002df32340000000000000000000000000000000000000000000000000000000000fdfd000000000000000000000000000000000000000000000000000000000000'],
            'result' => ['0xdf32340000000000000000000000000000000000000000000000000000000000', '0xfdfd000000000000000000000000000000000000000000000000000000000000'],
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->testJsonMethodString = file_get_contents(__DIR__ . '/../Fixtures/game_token_approve_function.json');

        // Error: Using $this when not in object context
        // $this->abi = new Ethabi([
        //     'address' => Address::class,
        //     'bool' => Boolean::class,
        //     'bytes' => Bytes::class,
        //     'int' => Integer::class,
        //     'string' => Str::class,
        //     'uint' => Uinteger::class,
        // ]);

        $this->abi = new Ethabi([
            'address' => new Address(),
            'bool' => new Boolean(),
            'bytes' => new Bytes(),
            'dynamicBytes' => new DynamicBytes(),
            'int' => new Integer(),
            'string' => new Str(),
            'uint' => new Uinteger(),
        ]);
    }

    /** @test */
    public function encode_function_signature(): void
    {
        $abi = $this->abi;
        $str = $abi->encodeFunctionSignature('baz(uint32,bool)');

        $this->assertEquals($str, '0xcdcd77c0');

        $json = json_decode($this->testJsonMethodString);
        $methodString = Utils::jsonMethodToString($json);
        $str = $abi->encodeFunctionSignature($methodString);

        $this->assertEquals($str, '0x095ea7b3');

        $str = $abi->encodeFunctionSignature('bar(bytes3[2])');

        $this->assertEquals($str, '0xfce353f6');

        $str = $abi->encodeFunctionSignature('sam(bytes,bool,uint256[])');

        $this->assertEquals($str, '0xa5643bf2');
    }

    /** @test */
    public function encode_event_signature(): void
    {
        $abi = $this->abi;
        $str = $abi->encodeEventSignature('baz(uint32,bool)');

        $this->assertEquals($str, '0xcdcd77c0992ec5bbfc459984220f8c45084cc24d9b6efed1fae540db8de801d2');

        $json = json_decode($this->testJsonMethodString);
        $methodString = Utils::jsonMethodToString($json);
        $str = $abi->encodeEventSignature($methodString);

        $this->assertEquals($str, '0x095ea7b334ae44009aa867bfb386f5c3b4b443ac6f0ee573fa91c4608fbadfba');
    }

    /** @test */
    public function encode_parameter(): void
    {
        $abi = $this->abi;

        $this->assertEquals('0xffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', $abi->encodeParameter('int256', '-1'));
    }

    /** @test */
    public function encode_parameters(): void
    {
        $abi = $this->abi;

        foreach ($this->encodingTests as $test) {
            $this->assertEquals($test['result'], $abi->encodeParameters($test['params'][0], $test['params'][1]));
        }
    }

    /** @test */
    public function decode_parameter(): void
    {
        $abi = $this->abi;

        $this->assertEquals('16', $abi->decodeParameter('uint256', '0x0000000000000000000000000000000000000000000000000000000000000010'));
        $this->assertEquals('16', $abi->decodeParameter('uint256', '0x0000000000000000000000000000000000000000000000000000000000000010'));
    }

    /** @test */
    public function decode_parameters(): void
    {
        $abi = $this->abi;

        foreach ($this->decodingTests as $test) {
            $decoded = $abi->decodeParameters($test['params'][0], $test['params'][1]);

            foreach ($decoded as $key => $decoding) {
                if (!is_array($decoding)) {
                    $this->assertEquals($test['result'][$key], $decoding);
                } else {
                    foreach ($test['result'] as $rKey => $expected) {
                        $this->assertEquals($expected, $decoding[$rKey]);
                    }
                }
            }
        }
    }

    /**
     * test 33 bytes and 128 bytes string, see: https://github.com/sc0Vu/web3.php/issues/71
     * string generated from: https://www.lipsum.com/
     *
     * @test
     */
    public function issue_71(): void
    {
        $abi = $this->abi;
        $specialString = 'Lorem ipsum dolor sit amet metus.';
        $encodedString = $abi->encodeParameter('string', $specialString);
        $this->assertEquals($specialString, $abi->decodeParameter('string', $encodedString));

        $specialString = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce pulvinar quam felis, suscipit posuere neque aliquam in cras amet.';
        $encodedString = $abi->encodeParameter('string', $specialString);
        $this->assertEquals($specialString, $abi->decodeParameter('string', $encodedString));
    }
}
