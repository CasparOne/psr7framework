<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AuraRouterAdapter.
 */
class AuraRouterAdapter implements RouterInterface
{
    private $aura;

    /**
     * AuraRouterAdapter constructor.
     *
     * @param RouterContainer $aura
     */
    public function __construct(RouterContainer $aura)
    {
        $this->aura = $aura;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @throws RequestNotMatchedException
     *
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        $matcher = $this->aura->getMatcher();
        if ($route = $matcher->match($request)) {
            return  new Result($route->name, $route->handler, $route->attributes);
        }

        throw new RequestNotMatchedException($request);
    }

    /**
     * @param $name
     * @param array $params
     *
     * @return string
     *
     * @throws RouteNotFoundException
     */
    public function generate($name, array $params = []): string
    {
        $generator = $this->aura->getGenerator();
        try {
            return $generator->generate($name, $params);
        } catch (RouteNotFound $exception) {
            throw new RouteNotFoundException($name, $params, $exception);
        }
    }

    public function addRoute(RouteData $routeData)
    {
        $map = $this->aura->getMap();
        $route = new Route();
        $route->name($routeData->name);
        $route->path($routeData->path);
        $route->handler($routeData->handler);
        foreach ($routeData->options as $key => $value) {
            switch ($key) {
                case 'tokens':
                    $route->tokens($value);
                    break;
                case 'defaults':
                    $route->defaults($value);
                    break;
                case 'wildcard':
                    $route->wildcard($value);
                    break;
                default:
                    throw new \InvalidArgumentException('Undefined option "'.$key.'"');
            }
        }

        if ($routeData->methods) {
            $route->allows($routeData->methods);
        }
        $map->addRoute($route);
    }
}
