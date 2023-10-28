<?php

/**
 * bbs.cgi bridge
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function bridge(Request $request): Response
{
    // Require POST method
    if ($request->getMethod() !== 'POST') {
        // 405 Method Not Allowed
        return new Response('', 405, [
            'Allow' => 'POST',
        ]);
    }

    ob_start();
    require __DIR__ . '/../test/bbs.php';
    $response = new Response(ob_get_clean(), 200, [
        'Content-Type' => 'text/html',
    ]);
    $response->setCharset('Shift_JIS');

    return $response;
}
