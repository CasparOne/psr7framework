<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Pipeline
 * @package Framework\Http\Pipeline
 */
class Pipeline
{
    private $middleware = [];

    /**
     * @param callable $middleware
     */
    public function pipe(callable $middleware) : void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $default) : ResponseInterface
    {
        return $this->next($request, $default);
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    private function next(ServerRequestInterface $request, callable $default) : ResponseInterface
    {
        if (!$current = array_shift($this->middleware)) {
            $default($request);
        };
        return $current($request, function (ServerRequestInterface $request) use ($default) {
            return $this->next($request, $default);
        });
    }

}