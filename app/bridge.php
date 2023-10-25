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
    ob_start();
    require __DIR__ . '/../test/bbs.php';
    $response = new Response(ob_get_clean());
    $response->setCharset('Shift_JIS');

    return $response;
}
