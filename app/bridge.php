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

    $loader = new Twig\Loader\FilesystemLoader('templates/compat/', __DIR__);
    $twig = new Twig\Environment($loader);

    $blocker = new Blocker\ChainBlocker([
        new Blocker\TimeParameterBlocker(),
        new Blocker\BoardParameterBlocker(),
    ]);
    if ($blocker->isBlock($request)) {
        $error = $twig->render('error.html.twig', [
            'message' => $blocker->message()
        ]);
        $response = new Response(mb_convert_encoding($error, 'SJIS-win', 'UTF-8'));
        $response->setCharset('Shift_JIS');
        return $response;
    }

    ob_start();
    require __DIR__ . '/../test/bbs.php';
    $response = new Response(ob_get_clean(), 200, [
        'Content-Type' => 'text/html',
    ]);
    $response->setCharset('Shift_JIS');

    return $response;
}
