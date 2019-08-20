<?php


namespace Framework\Http\Router;

/**
 * Class RouteCollection
 * @package Framework\Http\Router
 */
class RouteCollection
{
    private $routes = [];

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function any($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->routes[] = new Route($name, $pattern, $handler, [], $tokens);
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function get($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->routes[] = new Route($name, $pattern, $handler, ['GET'], $tokens);
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function post($name, $pattern, $handler, array $tokens = []) : void
    {
        $this->routes[] = new Route($name, $pattern, $handler, ['POST'], $tokens);
    }

    /**
     * @return array
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }
}