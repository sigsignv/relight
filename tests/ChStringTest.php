<?php

declare(strict_types=1);

namespace Relight\Tests;

use PHPUnit\Framework\TestCase;
use Relight\ChString;

final class ChStringTest extends TestCase
{
    public function testChString(): void
    {
        $s = "\x8C\x66\x8E\xA6\x94\xC2"; // 掲示板 (encoding: SJIS-win)
        $chs = new ChString($s);
        $this->assertEquals('掲示板', strval($chs));
    }

    public function testDecodeEntities(): void
    {
        $a = [
            '&amp;' => '&',
            '&quot;' => '"',
            '&#039;' => "'",
            '&apos;' => "'",
            '&lt;' => '<',
            '&gt;' => '>',
            '&#32;' => ' ',
            '&#47;' => '/',
            '&yen;' => '¥',
            '&copy;' => '©',
            '&#x3042;' => 'あ',
        ];
        $chs = new ChString(\implode('', \array_keys($a)));
        $this->assertEquals(\implode('', \array_values($a)), strval($chs));
    }

    public function testLength(): void
    {
        $chs = new ChString('掲示板', 'UTF-8'); // => \x8C\x66\x8E\xA6\x94\xC2
        $this->assertEquals(6, $chs->length());
    }

    public function testEmojiLength(): void
    {
        $chs = new ChString("\u{1F341}\u{1F430}", 'UTF-8'); // => &#x1F341;&#x1F430;
        $this->assertEquals(18, $chs->length());
    }
}
