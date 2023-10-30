<?php

/**
 * Board parameter based blocker
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

namespace Relight\Blocker;

use Relight\Blocker\BlockerInterface;
use Symfony\Component\HttpFoundation\Request;

class BoardParameterBlocker implements BlockerInterface
{
    public function isBlock(Request $request): bool
    {
        // bbs.cgi     : $_POST['bbs']
        // post-v2.php : $_POST['board']
        $board = $request->request->get('bbs') ?? $request->request->get('board', '');

        // 掲示板のディレクトリは英数字のみ
        if (preg_match("/[^0-9A-Za-z]/u", $board)) {
            return true;
        }

        return false;
    }

    public function message(): string
    {
        return 'ＢＢＳ名が不正です！';
    }
}
