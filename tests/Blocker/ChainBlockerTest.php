<?php

declare(strict_types=1);

namespace Relight\Tests;

use PHPUnit\Framework\TestCase;
use Relight\Blocker;
use Relight\Blocker\ChainBlocker;
use Symfony\Component\HttpFoundation\Request;

final class ChainBlockerTest extends TestCase
{
    public function testChainBlocker(): void
    {
        $blocker = new ChainBlocker([]);
        $request = new Request();
        $this->assertFalse($blocker->isBlock($request));
    }

    public function testArguments(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $blocker = new ChainBlocker([
            'Not BlockerInterface object'
        ]);
    }

    public function testMessage(): void
    {
        $blocker = new Blocker\BoardParameterBlocker();
        $chain = new ChainBlocker([
            $blocker,
        ]);
        $request = new Request([], [
            'bbs' => '_invalid_name_',
        ]);
        $this->assertTrue($chain->isBlock($request));
        $this->assertEquals($blocker->message(), $chain->message());
    }
}
