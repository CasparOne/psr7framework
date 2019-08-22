<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;

class Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request) : Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ( !is_null($result = $route->match($request)) ) {
                return $result;
            }
        }
        throw new RequestNotMatchedException($request);
    }

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