<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Tests\TestCase;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Providers\HttpProvider;
use Web3\Methods\Web3\ClientVersion;

class HttpProviderTest extends TestCase
{
    /** @test */
    public function set_request_manager(): void
    {
        $requestManager = new HttpRequestManager('http://localhost:8545');
        $provider = new HttpProvider($requestManager);

        $this->assertEquals($provider->requestManager->host, 'http://localhost:8545');

        $requestManager = new HttpRequestManager($this->testRinkebyHost);
        $provider->requestManager = $requestManager;

        $this->assertEquals($provider->requestManager->host, 'http://localhost:8545');
    }

    /** @test */
    public function send(): void
    {
        $requestManager = new HttpRequestManager($this->testHost);
        $provider = new HttpProvider($requestManager);
        $method = new ClientVersion([]);

        [$err, $version] = $provider->send($method);

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($version));
    }

    /** @test */
    public function batch_fails_if_not_batchin_provider(): void
    {
        $this->expectException(RuntimeException::class);

        $requestManager = new HttpRequestManager($this->testHost);
        $provider = new HttpProvider($requestManager);
        $method = new ClientVersion([]);

        $provider->execute();
    }

    /** @test */
    public function successful_batch(): void
    {
        $requestManager = new HttpRequestManager($this->testHost);
        $provider = new HttpProvider($requestManager);
        $method = new ClientVersion([]);
        $provider->batch(true);
        $provider->send($method, null);
        $provider->send($method, null);

        [$err, $data] = $provider->execute();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertEquals($data[0], $data[1]);
    }
}
