<?php

/**
 * Time parameter based blocker
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

namespace Relight\Blocker;

use Relight\Blocker\BlockerInterface;
use Symfony\Component\HttpFoundation\Request;

class TimeParameterBlocker implements BlockerInterface
{
    public function isBlock(Request $request): bool
    {
        // post-v2.php には 'time' パラメーターがない
        if ($request->getPathInfo() !== '/test/bbs.cgi') {
            return false;
        }

        $time = $request->request->get('time', -1);
        $r = filter_var($time, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 0,
            ],
            'flags' => FILTER_NULL_ON_FAILURE,
        ]);

        return is_null($r);
    }

    public function message(): string
    {
        return 'フォーム情報が不正です！';
    }
}
