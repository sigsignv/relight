<?php

declare(strict_types=1);

namespace Relight\Tests;

use PHPUnit\Framework\TestCase;
use Relight\BoardConfig;

final class BoardConfigTest extends TestCase
{
    public function testBoardConfig(): void
    {
        $config = new BoardConfig();
        $array = $config->toArray();
        $this->assertIsArray($array);
        $this->assertEmpty($array);
    }

    public function testGet(): void
    {
        $config = new BoardConfig([
            'TEST_KEY' => 'TEST_VALUE',
        ]);
        $this->assertEquals('TEST_VALUE', $config->get('TEST_KEY'));
    }

    public function testGetDefault(): void
    {
        $config = new BoardConfig();
        $this->assertEquals('DEFAULT_VALUE', $config->get('NOT_EXISTS_KEY', 'DEFAULT_VALUE'));
    }

    public function testSet(): void
    {
        $config = new BoardConfig();
        $config->set('TEST_KEY', 'TEST_VALUE');
        $this->assertArrayHasKey('TEST_KEY', $config->toArray());
    }

    public function testHas(): void
    {
        $config = new BoardConfig([
            'TEST_KEY' => 'TEST_VALUE',
        ]);
        $this->assertTrue($config->has('TEST_KEY'));
    }

    public function testNotHas(): void
    {
        $config = new BoardConfig([
            'OTHER_KEY' => 'OTHER_VALUE',
        ]);
        $this->assertFalse($config->has('TEST_KEY'));
    }

    public function testRemove(): void
    {
        $config = new BoardConfig([
            'TEST_KEY' => 'TEST_VALUE',
        ]);
        $config->remove('TEST_KEY');
        $this->assertArrayNotHasKey('TEST_KEY', $config->toArray());
    }

    public function testLoadJson(): void
    {
        $config = new BoardConfig();
        $config->loadJson(__DIR__ . '/../test/board/setting.json');
        $this->assertTrue($config->has('BBS_TITLE'));
    }

    public function testLoadJsonFailed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $config = new BoardConfig();
        $config->loadJson(__DIR__ . '/path/to/not-exists.json');
    }

    public function testCount(): void
    {
        $config = new BoardConfig([
            'TEST_KEY' => 'TEST_VALUE',
        ]);
        $this->assertCount(1, $config);
    }

    public function testGetIterator(): void
    {
        $list = [];
        $config = new BoardConfig([
            'TEST_KEY' => 'TEST_VALUE',
            'OTHER_KEY' => 'OTHER_VALUE',
        ]);
        foreach ($config as $key => $value) {
            $list[] = $key;
            $list[] = $value;
        }
        $this->assertCount(4, $list);
    }
}
