<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 04/11/2019
 * Time: 21:32
 */

namespace Framework;

use Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Class Router
 * @package Framework
 */
class Router
{

    /**
     * @var FastRouteRouter
     */
    private $router;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param string $path
     * @param callable|string $callable
     * @param string $name
     */
    public function get(string $path, $callable, string $name)
    {

        $this->router->addRoute(new ZendRoute($path, $callable, ['GET'], $name));
    }

    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);
        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }
        return null;
    }

    /**
     * @param string $name
     * @param array $params
     * @return string|null
     */
    public function generateURI(string $name, array $params): ?string
    {
        return $this->router->generateUri($name, $params);
    }
}
