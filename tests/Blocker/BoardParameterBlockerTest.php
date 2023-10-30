<?php

declare(strict_types=1);

namespace Relight\Tests;

use PHPUnit\Framework\TestCase;
use Relight\Blocker\BoardParameterBlocker;
use Symfony\Component\HttpFoundation\Request;

final class BoardParameterBlockerTest extends TestCase
{
    protected $blocker;

    protected function setUp(): void
    {
        $this->blocker = new BoardParameterBlocker();
    }

    public function testBoardParameterBlocker(): void
    {
        $request = Request::create('/test/post-v2.php', 'POST', [
            'board' => 'name',
        ]);
        $this->assertFalse($this->blocker->isBlock($request));
    }

    public function testIsBlock(): void
    {
        $request = Request::create('/test/bbs.cgi', 'POST', [
            'bbs' => '_invalid_name_',
        ]);
        $this->assertTrue($this->blocker->isBlock($request));
    }

    public function testIsNotBlock(): void
    {
        $request = Request::create('/test/bbs.cgi', 'POST', [
            'bbs' => 'validNAME1',
        ]);
        $this->assertFalse($this->blocker->isBlock($request));
    }

    public function testMessage(): void
    {
        $this->assertIsString($this->blocker->message());
    }
}
