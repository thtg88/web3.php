<?php

namespace Web3\Tests\Unit;

use InvalidArgumentException;
use stdClass;
use Web3\Tests\TestCase;
use phpseclib\Math\BigInteger as BigNumber;
use Web3\Utils;

class UtilsTest extends TestCase
{
    /**
     * 'hello world'
     * you can check by call pack('H*', $hex)
     */
    protected string $testHex = '68656c6c6f20776f726c64';

    /**
     * from GameToken approve function
     */
    protected string $testJsonMethodString;

    /**
     * see: https://github.com/sc0Vu/web3.php/issues/112
     */
    protected string $testIssue112Json = '';

    public function setUp(): void
    {
        parent::setUp();

        $this->testJsonMethodString = file_get_contents(__DIR__ . '/../Fixtures/game_token_approve_function.json');
        $this->testIssue112Json = file_get_contents(__DIR__ . '/../Fixtures/issue_112.json');
    }

    /** @test */
    public function to_hex(): void
    {
        $this->assertEquals($this->testHex, Utils::toHex('hello world'));
        $this->assertEquals('0x' . $this->testHex, Utils::toHex('hello world', true));

        $this->assertEquals('0x927c0', Utils::toHex(0x0927c0, true));
        $this->assertEquals('0x927c0', Utils::toHex('600000', true));
        $this->assertEquals('0x927c0', Utils::toHex(600000, true));
        $this->assertEquals('0x927c0', Utils::toHex(new BigNumber(600000), true));

        $this->assertEquals('0xea60', Utils::toHex(0x0ea60, true));
        $this->assertEquals('0xea60', Utils::toHex('60000', true));
        $this->assertEquals('0xea60', Utils::toHex(60000, true));
        $this->assertEquals('0xea60', Utils::toHex(new BigNumber(60000), true));

        $this->assertEquals('0x', Utils::toHex(0x00, true));
        $this->assertEquals('0x', Utils::toHex('0', true));
        $this->assertEquals('0x', Utils::toHex(0, true));
        $this->assertEquals('0x', Utils::toHex(new BigNumber(0), true));

        $this->assertEquals('0x30', Utils::toHex(48, true));
        $this->assertEquals('0x30', Utils::toHex('48', true));
        $this->assertEquals('30', Utils::toHex(48));
        $this->assertEquals('30', Utils::toHex('48'));

        $this->assertEquals('0x30', Utils::toHex(new BigNumber(48), true));
        $this->assertEquals('0x30', Utils::toHex(new BigNumber('48'), true));
        $this->assertEquals('30', Utils::toHex(new BigNumber(48)));
        $this->assertEquals('30', Utils::toHex(new BigNumber('48')));

        $this->expectException(InvalidArgumentException::class);
        $hex = Utils::toHex(new stdClass());
    }

    /** @test */
    public function hex_to_bin(): void
    {
        $str = Utils::hexToBin($this->testHex);
        $this->assertEquals($str, 'hello world');

        $str = Utils::hexToBin('0x' . $this->testHex);
        $this->assertEquals($str, 'hello world');

        $str = Utils::hexToBin('0xe4b883e5bda9e7a59ee4bb99e9b1bc');
        $this->assertEquals($str, '七彩神仙鱼');
    }

    /** @test */
    public function is_zero_prefixed(): void
    {
        $isPrefixed = Utils::isZeroPrefixed($this->testHex);
        $this->assertEquals($isPrefixed, false);

        $isPrefixed = Utils::isZeroPrefixed('0x' . $this->testHex);
        $this->assertEquals($isPrefixed, true);
    }

    /** @test */
    public function is_address(): void
    {
        $isAddress = Utils::isAddress('ca35b7d915458ef540ade6068dfe2f44e8fa733c');
        $this->assertEquals($isAddress, true);

        $isAddress = Utils::isAddress('0xca35b7d915458ef540ade6068dfe2f44e8fa733c');
        $this->assertEquals($isAddress, true);

        $isAddress = Utils::isAddress('0Xca35b7d915458ef540ade6068dfe2f44e8fa733c');
        $this->assertEquals($isAddress, true);

        $isAddress = Utils::isAddress('0XCA35B7D915458EF540ADE6068DFE2F44E8FA733C');
        $this->assertEquals($isAddress, true);

        $isAddress = Utils::isAddress('0xCA35B7D915458EF540ADE6068DFE2F44E8FA733C');
        $this->assertEquals($isAddress, true);

        $isAddress = Utils::isAddress('0xCA35B7D915458EF540ADE6068DFE2F44E8FA73cc');
        $this->assertEquals($isAddress, false);
    }

    /** @test */
    public function is_address_checksum(): void
    {
        $isAddressChecksum = Utils::isAddressChecksum('0x52908400098527886E0F7030069857D2E4169EE7');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0x8617E340B3D01FA5F11F306F4090FD50E238070D');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0xde709f2102306220921060314715629080e2fb77');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0x27b1fdb04752bbc536007a920d24acb045561c26');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0x5aAeb6053F3E94C9b9A09f33669435E7Ef1BeAed');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0x5aAeb6053F3E94C9b9A09f33669435E7Ef1BeAed');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0xfB6916095ca1df60bB79Ce92cE3Ea74c37c5d359');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0xdbF03B407c01E7cD3CBea99509d93f8DDDC8C6FB');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0xD1220A0cf47c7B9Be7A2E6BA89F429762e7b9aDb');
        $this->assertEquals($isAddressChecksum, true);

        $isAddressChecksum = Utils::isAddressChecksum('0XD1220A0CF47C7B9BE7A2E6BA89F429762E7B9ADB');
        $this->assertEquals($isAddressChecksum, false);

        $isAddressChecksum = Utils::isAddressChecksum('0xd1220a0cf47c7b9be7a2e6ba89f429762e7b9adb');
        $this->assertEquals($isAddressChecksum, false);
    }

    /** @test */
    public function to_checksum_address(): void
    {
        $checksumAddressTest = [
            // All caps
            '0x52908400098527886E0F7030069857D2E4169EE7',
            '0x8617E340B3D01FA5F11F306F4090FD50E238070D',
            // All Lower
            '0xde709f2102306220921060314715629080e2fb77',
            '0x27b1fdb04752bbc536007a920d24acb045561c26',
            // Normal
            '0x5aAeb6053F3E94C9b9A09f33669435E7Ef1BeAed',
            '0xfB6916095ca1df60bB79Ce92cE3Ea74c37c5d359',
            '0xdbF03B407c01E7cD3CBea99509d93f8DDDC8C6FB',
            '0xD1220A0cf47c7B9Be7A2E6BA89F429762e7b9aDb',
        ];

        for ($i=0; $i<count($checksumAddressTest); $i++) {
            $checksumAddress = Utils::toChecksumAddress(strtolower($checksumAddressTest[$i]));

            $this->assertEquals($checksumAddressTest[$i], $checksumAddress);
        }
    }

    /** @test */
    public function strip_zero(): void
    {
        $str = Utils::stripZero($this->testHex);

        $this->assertEquals($str, $this->testHex);

        $str = Utils::stripZero('0x' . $this->testHex);

        $this->assertEquals($str, $this->testHex);
    }

    /** @test */
    public function sha3(): void
    {
        $str = Utils::sha3('');
        $this->assertNull($str);

        $str = Utils::sha3('baz(uint32,bool)');
        $this->assertEquals(mb_substr($str, 0, 10), '0xcdcd77c0');
    }

    /** @test */
    public function to_wei(): void
    {
        $bn = Utils::toWei('0x1', 'wei');
        $this->assertEquals('1', $bn->toString());

        $bn = Utils::toWei('18', 'wei');
        $this->assertEquals('18', $bn->toString());

        $bn = Utils::toWei('1', 'ether');
        $this->assertEquals('1000000000000000000', $bn->toString());

        $bn = Utils::toWei('0x5218', 'wei');
        $this->assertEquals('21016', $bn->toString());

        $bn = Utils::toWei('0.000012', 'ether');
        $this->assertEquals('12000000000000', $bn->toString());

        $bn = Utils::toWei('0.1', 'ether');
        $this->assertEquals('100000000000000000', $bn->toString());

        $bn = Utils::toWei('1.69', 'ether');
        $this->assertEquals('1690000000000000000', $bn->toString());

        $bn = Utils::toWei('0.01', 'ether');
        $this->assertEquals('10000000000000000', $bn->toString());

        $bn = Utils::toWei('0.002', 'ether');
        $this->assertEquals('2000000000000000', $bn->toString());

        $bn = Utils::toWei('-0.1', 'ether');
        $this->assertEquals('-100000000000000000', $bn->toString());

        $bn = Utils::toWei('-1.69', 'ether');
        $this->assertEquals('-1690000000000000000', $bn->toString());

        $bn = Utils::toWei('', 'ether');
        $this->assertEquals('0', $bn->toString());

        try {
            $bn = Utils::toWei('0x5218', new stdClass());
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('toWei unit must be string.', $e->getMessage());
        }

        try {
            $bn = Utils::toWei('0x5218', 'test');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('toWei doesn\'t support test unit.', $e->getMessage());
        }

        try {
            // out of limit
            $bn = Utils::toWei(-1.6977, 'kwei');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('toWei number must be string or bignumber.', $e->getMessage());
        }
    }

    /** @test */
    public function to_ether(): void
    {
        list($bnq, $bnr) = Utils::toEther('0x1', 'wei');

        $this->assertEquals($bnq->toString(), '0');
        $this->assertEquals($bnr->toString(), '1');

        list($bnq, $bnr) = Utils::toEther('18', 'wei');

        $this->assertEquals($bnq->toString(), '0');
        $this->assertEquals($bnr->toString(), '18');

        list($bnq, $bnr) = Utils::toEther('1', 'kether');

        $this->assertEquals($bnq->toString(), '1000');
        $this->assertEquals($bnr->toString(), '0');

        list($bnq, $bnr) = Utils::toEther('0x5218', 'wei');

        $this->assertEquals($bnq->toString(), '0');
        $this->assertEquals($bnr->toString(), '21016');

        list($bnq, $bnr) = Utils::toEther('0x5218', 'ether');

        $this->assertEquals($bnq->toString(), '21016');
        $this->assertEquals($bnr->toString(), '0');
    }

    /** @test */
    public function from_wei(): void
    {
        list($bnq, $bnr) = Utils::fromWei('1000000000000000000', 'ether');

        $this->assertEquals($bnq->toString(), '1');
        $this->assertEquals($bnr->toString(), '0');

        list($bnq, $bnr) = Utils::fromWei('18', 'wei');

        $this->assertEquals($bnq->toString(), '18');
        $this->assertEquals($bnr->toString(), '0');

        list($bnq, $bnr) = Utils::fromWei(1, 'femtoether');

        $this->assertEquals($bnq->toString(), '0');
        $this->assertEquals($bnr->toString(), '1');

        list($bnq, $bnr) = Utils::fromWei(0x11, 'nano');

        $this->assertEquals($bnq->toString(), '0');
        $this->assertEquals($bnr->toString(), '17');

        list($bnq, $bnr) = Utils::fromWei('0x5218', 'kwei');

        $this->assertEquals($bnq->toString(), '21');
        $this->assertEquals($bnr->toString(), '16');

        try {
            list($bnq, $bnr) = Utils::fromWei('0x5218', new stdClass());
        } catch (InvalidArgumentException $e) {
            $this->assertTrue($e !== null);
        }

        try {
            list($bnq, $bnr) = Utils::fromWei('0x5218', 'test');
        } catch (InvalidArgumentException $e) {
            $this->assertTrue($e !== null);
        }
    }

    /** @test */
    public function json_method_to_string(): void
    {
        $json = json_decode($this->testJsonMethodString);
        $methodString = Utils::jsonMethodToString($json);

        $this->assertEquals($methodString, 'approve(address,uint256)');

        $json = json_decode($this->testJsonMethodString, true);
        $methodString = Utils::jsonMethodToString($json);

        $this->assertEquals($methodString, 'approve(address,uint256)');

        $methodString = Utils::jsonMethodToString([
            'name' => 'approve(address,uint256)',
        ]);
        $this->assertEquals($methodString, 'approve(address,uint256)');

        $this->expectException(InvalidArgumentException::class);
        $methodString = Utils::jsonMethodToString('test');
    }

    /** @test */
    public function json_to_array(): void
    {
        $decodedJson = json_decode($this->testJsonMethodString);
        $jsonArray = Utils::jsonToArray($decodedJson);
        $jsonAssoc = json_decode($this->testJsonMethodString, true);
        $jsonArray2 = Utils::jsonToArray($jsonAssoc);
        $this->assertEquals($jsonAssoc, $jsonArray);
        $this->assertEquals($jsonAssoc, $jsonArray2);

        $jsonAssoc = json_decode($this->testIssue112Json, true);
        $jsonArray = Utils::jsonToArray($jsonAssoc);
        $this->assertEquals($jsonAssoc, $jsonArray);
    }

    /** @test */
    public function is_hex(): void
    {
        $isHex = Utils::isHex($this->testHex);

        $this->assertTrue($isHex);

        $isHex = Utils::isHex('0x' . $this->testHex);

        $this->assertTrue($isHex);

        $isHex = Utils::isHex('hello world');

        $this->assertFalse($isHex);
    }

    /** @test */
    public function is_negative(): void
    {
        $isNegative = Utils::isNegative('-1');
        $this->assertTrue($isNegative);

        $isNegative = Utils::isNegative('1');
        $this->assertFalse($isNegative);
    }

    /** @test */
    public function to_bn(): void
    {
        $bn = Utils::toBn('');
        $this->assertEquals($bn->toString(), '0');

        $bn = Utils::toBn(11);
        $this->assertEquals($bn->toString(), '11');

        $bn = Utils::toBn('0x12');
        $this->assertEquals($bn->toString(), '18');

        $bn = Utils::toBn('-0x12');
        $this->assertEquals($bn->toString(), '-18');

        $bn = Utils::toBn(0x12);
        $this->assertEquals($bn->toString(), '18');

        $bn = Utils::toBn('ae');
        $this->assertEquals($bn->toString(), '174');

        $bn = Utils::toBn('-ae');
        $this->assertEquals($bn->toString(), '-174');

        $bn = Utils::toBn('-1');
        $this->assertEquals($bn->toString(), '-1');

        $bn = Utils::toBn('-0.1');
        $this->assertEquals(count($bn), 4);
        $this->assertEquals($bn[0]->toString(), '0');
        $this->assertEquals($bn[1]->toString(), '1');
        $this->assertEquals($bn[2], 1);
        $this->assertEquals($bn[3]->toString(), '-1');

        $bn = Utils::toBn(-0.1);
        $this->assertEquals(count($bn), 4);
        $this->assertEquals($bn[0]->toString(), '0');
        $this->assertEquals($bn[1]->toString(), '1');
        $this->assertEquals($bn[2], 1);
        $this->assertEquals($bn[3]->toString(), '-1');

        $bn = Utils::toBn('0.1');
        $this->assertEquals(count($bn), 4);
        $this->assertEquals($bn[0]->toString(), '0');
        $this->assertEquals($bn[1]->toString(), '1');
        $this->assertEquals($bn[2], 1);
        $this->assertEquals($bn[3], false);

        $bn = Utils::toBn('-1.69');
        $this->assertEquals(count($bn), 4);
        $this->assertEquals($bn[0]->toString(), '1');
        $this->assertEquals($bn[1]->toString(), '69');
        $this->assertEquals($bn[2], 2);
        $this->assertEquals($bn[3]->toString(), '-1');

        $bn = Utils::toBn(-1.69);
        $this->assertEquals($bn[0]->toString(), '1');
        $this->assertEquals($bn[1]->toString(), '69');
        $this->assertEquals($bn[2], 2);
        $this->assertEquals($bn[3]->toString(), '-1');

        $bn = Utils::toBn('1.69');
        $this->assertEquals(count($bn), 4);
        $this->assertEquals($bn[0]->toString(), '1');
        $this->assertEquals($bn[1]->toString(), '69');
        $this->assertEquals($bn[2], 2);
        $this->assertEquals($bn[3], false);

        $bn = Utils::toBn(new BigNumber(1));
        $this->assertEquals($bn->toString(), '1');

        $this->expectException(InvalidArgumentException::class);
        $bn = Utils::toBn(new stdClass());
    }
}
