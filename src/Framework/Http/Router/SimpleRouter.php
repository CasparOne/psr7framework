<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;

/**
 * Class Router
 * @package Framework\Http\Router
 */
class SimpleRouter implements RouterInterface
{
    private $routes;

    /**
     * Router constructor.
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Result
     * @throws RequestNotMatchedException
     */
    public function match(ServerRequestInterface $request) : Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ( !is_null($result = $route->match($request)) ) {
                return $result;
            }
        }
        throw new RequestNotMatchedException($request);
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     * @throws RouteNotFoundException
     */
    public function generate($name, array $params = []) : string
    {
        foreach ($this->routes->getRoutes() as $route) {
           if (!is_null($url = $route->generate($name, $params)) ) {
               return $url;
           }
        }
        throw new RouteNotFoundException($name, $params);
    }

}