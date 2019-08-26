<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use function is_string;

/**
 * Class MiddlewareResolver
 * @package Framework\Http
 */
class MiddlewareResolver
{
    /**
     * If @handler is't an object returns function which creates one
     * @param $handler
     * @return callable
     */
    public function resolve($handler) : callable
    {
        if (is_string($handler)) {
            return function (ServerRequestInterface $request, callable $next) use ($handler) {
                $object = new $handler();
                return $object($request, $next);
            };
        }
        return $handler;
    }
}