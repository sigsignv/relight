<?php

declare(strict_types=1);

namespace Relight\Tests;

use PHPUnit\Framework\TestCase;
use Relight\RemoteHost;

final class RemoteHostTest extends TestCase
{
    public function testInvalidAddress(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $host = new RemoteHost('_Invalid_address_');
    }

    public function testInvalidHostname(): void
    {
        $host = new RemoteHost('203.0.113.1', '_Invalid_hostname_');
        $this->assertEquals('', $host->getHostname());
    }

    public function testGetIP(): void
    {
        $host = new RemoteHost('203.0.113.1');
        $this->assertEquals('203.0.113.1', $host->getIP());
    }

    public function testHostname(): void
    {
        $host = new RemoteHost('203.0.113.1', 'example.com');
        $this->assertEquals('example.com', $host->getHostname());
    }

    public function testHostnameEmptyIfIPv6(): void
    {
        $host = new RemoteHost('2001:db8::1', 'example.com');
        $this->assertEquals('', $host->getHostname());
    }

    public function testIsIPv4(): void
    {
        $host = new RemoteHost('203.0.113.1');
        $this->assertTrue($host->isIPv4());
    }

    public function testIsNotIPv4(): void
    {
        $host = new RemoteHost('2001:db8::1');
        $this->assertFalse($host->isIPv4());
    }

    public function testIsIPv6(): void
    {
        $host = new RemoteHost('2001:db8::1');
        $this->assertTrue($host->isIPv6());
    }

    public function testIsNotIPv6(): void
    {
        $host = new RemoteHost('203.0.113.1');
        $this->assertFalse($host->isIPv6());
    }

    public function testString(): void
    {
        $host = new RemoteHost('203.0.113.1');
        $this->assertEquals('203.0.113.1', $host);
    }

    public function testStringWithHostname(): void
    {
        $host = new RemoteHost('203.0.113.1', 'example.com');
        $this->assertEquals('example.com (203.0.113.1)', $host);
    }

    public function testStringIPv6(): void
    {
        $host = new RemoteHost('2001:db8::1', 'example.com');
        $this->assertEquals('2001:db8::1', $host);
    }

    public function testIncluded(): void
    {
        $host = new RemoteHost('203.0.113.1');
        $this->assertTrue($host->included('203.0.113.0/24'));
    }

    public function testNotIncluded(): void
    {
        $host = new RemoteHost('203.0.113.1');
        $this->assertFalse($host->included('2001:db8::/32'));
    }

    public function testIncludedArray(): void
    {
        $host = new RemoteHost('2001:db8::1');
        $this->assertTrue($host->included(['203.0.113.0/24', '2001:db8::/32']));
    }

    public function testMatch(): void
    {
        $host = new RemoteHost('203.0.113.1', 'example.com');
        $this->assertTrue($host->match('/(example)\.com$/'));
    }

    public function testNotMatch(): void
    {
        $host = new RemoteHost('203.0.113.1', 'example.com');
        $this->assertFalse($host->match('/(example)\.net$/'));
    }

    public function testMatchArray(): void
    {
        $host = new RemoteHost('203.0.113.1', 'example.com');
        $matches = [];

        $this->assertTrue($host->match(['/(example)\.net$/', '/(example)\.com$/'], $matches));
        $this->assertEquals('example', $matches[1]);
    }
}
