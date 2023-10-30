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

class ChainBlocker implements BlockerInterface
{
    private array $blockers;
    private string $message = '';

    public function __construct(array $blockers)
    {
        foreach ($blockers as $blocker) {
            if (!($blocker instanceof BlockerInterface)) {
                throw new \InvalidArgumentException('Require BlockerInterface instance');
            }
        }

        $this->blockers = $blockers;
    }

    public function isBlock(Request $request): bool
    {
        foreach ($this->blockers as $blocker) {
            if ($blocker->isBlock($request)) {
                $this->message = $blocker->message();
                return true;
            }
        }

        return false;
    }

    public function message(): string
    {
        return $this->message;
    }
}
