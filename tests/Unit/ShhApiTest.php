<?php

namespace Web3\Tests\Unit;

use RuntimeException;
use InvalidArgumentException;
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

        $shh->version(function ($err, $version) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }

            $this->assertTrue(is_string($version));
        });
    }

    /**
     * Comment because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function new_identity(): void
    // {
    //     $shh = $this->shh;

    //     $shh->newIdentity(function ($err, $identity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });
    // }

    /**
     * Comment because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function has_identity(): void
    // {
    //     $shh = $this->shh;
    //     $newIdentity = '0x' . implode('', array_fill(0, 120, '0'));

    //     $shh->hasIdentity($newIdentity, function ($err, $hasIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertFalse($hasIdentity);
    //     });

    //     $shh->newIdentity(function ($err, $identity) use (&$newIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $newIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->hasIdentity($newIdentity, function ($err, $hasIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($hasIdentity);
    //     });
    // }

    /** @test */
    // public function new_group(): void
    // {
    //     $shh = $this->shh;

    //     $shh->newGroup(function ($err, $group) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertEquals(mb_strlen($group), 132);
    //     });
    // }

    /** @test */
    // public function add_to_group(): void
    // {
    //     $shh = $this->shh;
    //     $newIdentity = '';

    //     $shh->newIdentity(function ($err, $identity) use (&$newIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $newIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->addToGroup($newIdentity, function ($err, $hasAdded) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($hasAdded);
    //     });
    // }

    /**
     * Comment because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function post(): void
    // {
    //     $shh = $this->shh;
    //     $fromIdentity = '';
    //     $toIdentity = '';

    //     // create fromIdentity and toIdentity to prevent unknown identity error
    //     $shh->newIdentity(function ($err, $identity) use (&$fromIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $fromIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });
    //     $shh->newIdentity(function ($err, $identity) use (&$toIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $toIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => "0x64",
    //         'ttl' => "0x64",
    //     ], function ($err, $isSent) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($isSent);
    //     });

    //     $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => 123,
    //         'ttl' => 123,
    //     ], function ($err, $isSent) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($isSent);
    //     });
    // }

    /**
     * Comment because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function new_filter(): void
    // {
    //     $shh = $this->shh;
    //     $toIdentity = '';

    //     // create toIdentity to prevent unknown identity error
    //     $shh->newIdentity(function ($err, $identity) use (&$toIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $toIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ], function ($err, $filterId) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_string($filterId));
    //     });

    //     $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => [null, "0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ], function ($err, $filterId) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_string($filterId));
    //     });

    //     $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"]],
    //     ], function ($err, $filterId) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_string($filterId));
    //     });
    // }

    /**
     * Comment because ganache-cli only implement shh_version.
     *
     * @test
     */
    // public function uninstall_filter(): void
    // {
    //     $shh = $this->shh;
    //     $toIdentity = '';
    //     $filter = '';

    //     // create toIdentity to prevent unknown identity error
    //     $shh->newIdentity(function ($err, $identity) use (&$toIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $toIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ], function ($err, $filterId) use (&$filter) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $filter = $filterId;

    //         $this->assertTrue(is_string($filterId));
    //     });

    //     $shh->uninstallFilter($filter, function ($err, $uninstalled) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($uninstalled);
    //     });
    // }

    /**
     * Comment because ganache-cli only implement shh_version.
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
    //     $shh->newIdentity(function ($err, $identity) use (&$toIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $toIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->newIdentity(function ($err, $identity) use (&$fromIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $fromIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ], function ($err, $filterId) use (&$filter) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $filter = $filterId;

    //         $this->assertTrue(is_string($filterId));
    //     });

    //     $shh->getFilterChanges($filter, function ($err, $changes) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_array($changes));
    //     });

    //     // try to post, but didn't get changes
    //     $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => "0x64",
    //         'ttl' => "0x64",
    //     ], function ($err, $isSent) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($isSent);
    //     });

    //     $shh->getFilterChanges($filter, function ($err, $changes) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_array($changes));
    //     });
    // }

    /**
     * Comment because ganache-cli only implement shh_version.
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
    //     $shh->newIdentity(function ($err, $identity) use (&$toIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $toIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->newIdentity(function ($err, $identity) use (&$fromIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $fromIdentity = $identity;

    //         $this->assertEquals(mb_strlen($identity), 132);
    //     });

    //     $shh->newFilter([
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //     ], function ($err, $filterId) use (&$filter) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $filter = $filterId;

    //         $this->assertTrue(is_string($filterId));
    //     });

    //     $shh->getMessages($filter, function ($err, $messages) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_array($messages));
    //     });

    //     $shh->post([
    //         'from' => $fromIdentity,
    //         'to' => $toIdentity,
    //         'topics' => ["0x776869737065722d636861742d636c69656e74", "0x4d5a695276454c39425154466b61693532"],
    //         'payload' => "0x7b2274797065223a226d6",
    //         'priority' => "0x64",
    //         'ttl' => "0x64",
    //     ], function ($err, $isSent) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue($isSent);
    //     });

    //     $shh->getMessages($filter, function ($err, $messages) use ($fromIdentity, $toIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(is_array($messages));
    //         $this->assertEquals($fromIdentity, $messages[0]->from);
    //         $this->assertEquals($toIdentity, $messages[0]->to);
    //         $this->assertEquals('0x07b2274797065223a226d6', $messages[0]->payload);
    //     });
    // }

    /**
     * We transform data and throw invalid argument exception
     * instead of runtime exception.
     *
     * @test
     */
    // public function wrong_param(): void
    // {
    //     $this->expectException(RuntimeException::class);

    //     $shh = $this->shh;

    //     $shh->hasIdentity('0', function ($err, $hasIdentity) {
    //         if ($err !== null) {
    //             return $this->fail($err->getMessage());
    //         }
    //         $this->assertTrue(true);
    //     });
    // }

    /** @test */
    public function unallowed_method(): void
    {
        $this->expectException(RuntimeException::class);

        $shh = $this->shh;

        $shh->hello(function ($err, $hello) {
            if ($err !== null) {
                return $this->fail($err->getMessage());
            }
            $this->assertTrue(true);
        });
    }

    /** @test */
    public function wrong_callback(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $shh = $this->shh;

        $shh->version();
    }
}
