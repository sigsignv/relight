<?php

/**
 * Blocker Interface
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

namespace Relight\Blocker;

use Symfony\Component\HttpFoundation\Request;

interface BlockerInterface
{
    public function isBlock(Request $request): bool;
    public function message(): string;
}
