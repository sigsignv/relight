<?php

/**
 * SJIS-win and UTF-8 string converter
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

namespace Relight;

class ChString
{
    private $raw;
    private $string;

    public function __construct(string $string, string $encoding = 'SJIS-win')
    {
        $this->raw = $encoding === 'SJIS-win' ? $string : '';
        $this->string = $encoding === 'UTF-8' ? $string : \mb_convert_encoding($string, 'UTF-8', $encoding);

        $this->string = \html_entity_decode($this->string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }

    public function length(): int
    {
        // BBS_MESSAGE_COUNT 等は文字数ではなくバイト数を基準としているため、あえて strlen() を使う
        return \strlen($this->toSJIS());
    }

    public function toSJIS(): string
    {
        if ($this->raw !== '') {
            return $this->raw;
        }

        $config = \mb_substitute_character();
        \mb_substitute_character('entity');
        $s = \mb_convert_encoding($this->string, 'SJIS-win', 'UTF-8');
        \mb_substitute_character($config);

        return $s;
    }

    public function toUTF8(): string
    {
        return $this->string;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
