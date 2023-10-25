<?php

/**
 * Route definitions
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('bbs_cgi', new Route('/test/bbs.cgi', [
    '_controller' => function () {
        return new Response('It works!');
    }
]));

return $routes;
