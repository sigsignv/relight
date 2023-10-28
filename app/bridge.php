<?php

/**
 * bbs.cgi bridge
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

use Relight\Blocker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function bridge(Request $request): Response
{
    // Require POST method
    if ($request->getMethod() !== 'POST') {
        return new Response('Method Not Allowed', 405, [
            'Allow' => 'POST',
            'Content-Type' => 'text/plain',
        ]);
    }

    ob_start();
    require __DIR__ . '/../test/bbs.php';

    $blocker = new Blocker\BoardParameterBlocker();
    if ($blocker->isBlock($request)) {
        Error2(mb_convert_encoding($blocker->message(), 'SJIS-win', 'UTF-8'));
    }

    $response = new Response(ob_get_clean(), 200, [
        'Content-Type' => 'text/html',
    ]);
    $response->setCharset('Shift_JIS');

    return $response;
}
