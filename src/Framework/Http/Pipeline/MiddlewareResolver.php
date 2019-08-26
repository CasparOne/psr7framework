<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;
use function is_string;

/**
 * Class MiddlewareResolver
 * @package Framework\Http
 */
class MiddlewareResolver
{

    public function resolve($handler) : callable
    {
        if (is_array($handler)) {
            return  $this->createPipe($handler);
        }

        if (is_string($handler)) {
            return function (ServerRequestInterface $request, callable $next) use ($handler) {
                $object = new $handler();
                return $object($request, $next);
            };
        }
        return $handler;
    }

    /**
     * @param array $handlers
     * @return Pipeline
     */
    protected function createPipe(array $handlers) : Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }

        return $pipeline;

    }
}