<?php

/**
 * Route definitions
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('bbs_cgi', new Route('/test/bbs.cgi', [
    '_encoding' => 'SJIS-win',
    '_controller' => function (Request $request) {
        require __DIR__ . '/bridge.php';
        return bridge($request);
    },
]));

return $routes;
