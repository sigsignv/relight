<?

/**
 *
 */

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\component\Routing;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$routes = new RouteCollection();
$routes->add('bbs_cgi', new Route('/test/bbs.cgi'));

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);

} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('Unexpected Error', 500);
}

$response->prepare($request);
$response->send();
