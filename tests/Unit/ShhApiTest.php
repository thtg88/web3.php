<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use Web3\Tests\TestCase;
use Web3\Shh;

class ShhApiTest extends TestCase
{
    protected Shh $shh;

    public function setUp(): void
    {
        parent::setUp();

        $this->shh = $this->web3->shh;
    }

    /** @test */
    public function version(): void
    {
        $shh = $this->shh;

        [$err, $version] = $shh->version();

        if ($err !== null) {
            $this->fail($err->getMessage());
        }

        $this->assertTrue(is_string($version));
    }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function new_identity(): void
    // {
    //     $shh = $this->shh;

    //     [$err, $identity] = $shh->newIdentity();

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertEquals(mb_strlen($identity), 132);
    // }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function has_identity(): void
    // {
    //     $shh = $this->shh;
    //     $newIdentity = '0x' . implode('', array_fill(0, 120, '0'));

    //     [$err, $hasIdentity] = $shh->hasIdentity($newIdentity);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertFalse($hasIdentity);

    //     [$err, $identity] = $shh->newIdentity();

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $newIdentity = $identity;

    //     $this->assertEquals(mb_strlen($identity), 132);

    //     [$err, $hasIdentity] = $shh->hasIdentity($newIdentity);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($hasIdentity);
    // }

    /** @test */
    // public function new_group(): void
    // {
    //     $shh = $this->shh;

    //     [$err, $group] = $shh->newGroup();

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertEquals(mb_strlen($group), 132);
    // }

    /** @test */
    // public function add_to_group(): void
    // {
    //     $shh = $this->shh;
    //     $newIdentity = '';
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $newIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);

    //     [$err, $hasAdded] = $shh->addToGroup($newIdentity);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($hasAdded);
    // }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function post(): void
    // {
    //     $shh = $this->shh;
    //     $fromIdentity = '';
    //     $toIdentity = '';
    //     // create fromIdentity and toIdentity to prevent unknown identity error
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $fromIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $toIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);

    //     [$err, $isSent] = $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => "0x64",
    //         'ttl' => "0x64",
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($isSent);

    //     [$err, $isSent] = $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => 123,
    //         'ttl' => 123,
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($isSent);
    // }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function new_filter(): void
    // {
    //     $shh = $this->shh;
    //     $toIdentity = '';
    //     // create toIdentity to prevent unknown identity error
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $toIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);

    //     [$err, $filterId] = $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_string($filterId));

    //     [$err, $filterId] = $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => [null, "0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_string($filterId));

    //     [$err, $filterId] = $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"]],
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_string($filterId));
    // }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function uninstall_filter(): void
    // {
    //     $shh = $this->shh;
    //     $toIdentity = '';
    //     $filter = '';
    //     // create toIdentity to prevent unknown identity error
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $toIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);
    //     [$err, $filterId] = $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ]);
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $filter = $filterId;
    //     $this->assertTrue(is_string($filterId));

    //     [$err, $uninstalled] = $shh->uninstallFilter($filter);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($uninstalled);
    // }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function get_filter_changes(): void
    // {
    //     $shh = $this->shh;
    //     $fromIdentity = '';
    //     $toIdentity = '';
    //     $filter = '';
    //     // create fromIdentity and toIdentity to prevent unknown identity error
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $toIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $fromIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);
    //     [$err, $filterId] = $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ]);
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $filter = $filterId;
    //     $this->assertTrue(is_string($filterId));

    //     [$err, $changes] = $shh->getFilterChanges($filter);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_array($changes));

    //     // try to post, but didn't get changes
    //     [$err, $isSent] = $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => "0x64",
    //         'ttl' => "0x64",
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($isSent);

    //     [$err, $changes] = $shh->getFilterChanges($filter);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_array($changes));
    // }

    /**
     * Commented because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function get_messages(): void
    // {
    //     $shh = $this->shh;
    //     $fromIdentity = '';
    //     $toIdentity = '';
    //     $filter = '';
    //     // create fromIdentity and toIdentity to prevent unknown identity error
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $toIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);
    //     [$err, $identity] = $shh->newIdentity();
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $fromIdentity = $identity;
    //     $this->assertEquals(mb_strlen($identity), 132);
    //     [$err, $filterId] = $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ]);
    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }
    //     $filter = $filterId;
    //     $this->assertTrue(is_string($filterId));

    //     [$err, $messages] = $shh->getMessages($filter);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_array($messages));

    //     [$err, $isSent] = $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => "0x64",
    //         'ttl' => "0x64",
    //     ]);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue($isSent);

    //     [$err, $messages] = $shh->getMessages($filter);

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(is_array($messages));
    //     $this->assertEquals($fromIdentity, $messages[0]->from);
    //     $this->assertEquals($toIdentity, $messages[0]->to);
    //     $this->assertEquals('0x07b2274797065223a226d6', $messages[0]->payload);
    // }

    /**
     * We transform data and throw invalid argument exception instead of runtime exception.
     *
     * @test
     */
    // public function wrong_param(): void
    // {
    //     $this->expectException(RuntimeException::class);

    //     $shh = $this->shh;

    //     [$err, $hasIdentity] = $shh->hasIdentity('0');

    //     if ($err !== null) {
    //         $this->fail($err->getMessage());
    //     }

    //     $this->assertTrue(true);
    // }
}
