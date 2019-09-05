<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;

/**
 * Interface Router.
 */
interface RouterInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @throws RequestNotMatchedException
     *
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * @param $name
     * @param array $params
     *
     * @return string
     *
     * @throws RouteNotFoundException
     */
    public function generate($name, array $params = []): string;

    public function addRoute($name, $path, $handler, array $methods, array $options);
}
