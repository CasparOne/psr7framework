<?php

namespace App\Http\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProfilerMiddleware
 * @package App\Http\Middleware
 */
class ProfilerMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $start = microtime(true);
        $response = $handler->handle($request);
        $stop = microtime(true);

        return $response->withHeader('X-Profiler-Time', $stop - $start);
    }
}
