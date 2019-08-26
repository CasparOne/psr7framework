<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProfilerMiddleware
 * @package App\Http\Middleware
 */
class ProfilerMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $start = microtime(true);
        /** @var ResponseInterface $response */
        $response = $next($request);
        $stop = microtime(true);

        return $response->withHeader('X-Profiler-Time', $stop - $start);
    }

}