<?php


namespace Framework\Http\Router;

use Framework\Http\Router\Route\RegExpRoute;
use Framework\Http\Router\Route\RouteInterface;
/**
 * Class RouteCollection
 * @package Framework\Http\Router
 */
class RouteCollection
{
    private $routes = [];

    public function addRoute(RouteInterface $route) : void
    {
        $this->routes = $route;
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function any($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->addRoute(new RegExpRoute($name, $pattern, $handler,[], $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function get($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->addRoute(new RegExpRoute($name, $pattern, $handler, ['GET'], $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function post($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->addRoute(new RegExpRoute($name, $pattern, $handler, ['POST'], $tokens));
    }

    /**
     * @return RegExpRoute[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}