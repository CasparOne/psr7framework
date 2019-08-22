<?php


namespace Framework\Http\Router;

/**
 * Class RouteCollection
 * @package Framework\Http\Router
 */
class RouteCollection
{
    private $routes = [];

    public function addRoute(Route $route) : void
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
        $this->addRoute(new Route($name, $pattern, $handler,[], $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function get($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->addRoute(new Route($name, $pattern, $handler, ['GET'], $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function post($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->addRoute(new Route($name, $pattern, $handler, ['POST'], $tokens));
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}