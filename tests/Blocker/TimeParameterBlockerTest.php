<?php

declare(strict_types=1);

namespace Relight\Tests;

use PHPUnit\Framework\TestCase;
use Relight\Blocker\TimeParameterBlocker;
use Symfony\Component\HttpFoundation\Request;

final class TimeParameterBlockerTest extends TestCase
{
    protected $blocker;

    protected function setUp(): void
    {
        $this->blocker = new TimeParameterBlocker();
    }

    public function testTimeParameterBlocker(): void
    {
        $request = Request::create('/test/post-v2.php', 'POST');
        $this->assertFalse($this->blocker->isBlock($request));
    }

    public function testIsBlockIfEmpty(): void
    {
        $request = Request::create('/test/bbs.cgi', 'POST');
        $this->assertTrue($this->blocker->isBlock($request));
    }

    public function testIsBlockIfInvalid(): void
    {
        $request = Request::create('/test/bbs.cgi', 'POST', [
            'time' => '1_invalid_2_time_3',
        ]);
        $this->assertTrue($this->blocker->isBlock($request));
    }

    public function testIsNotBlock(): void
    {
        $request = Request::create('/test/bbs.cgi', 'POST', [
            'time' => '0',
        ]);
        $this->assertFalse($this->blocker->isBlock($request));
    }

    public function testMessage(): void
    {
        $this->assertIsString($this->blocker->message());
    }
}
