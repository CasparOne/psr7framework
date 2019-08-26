<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

/**
 * Class Pipeline
 * @package Framework\Http\Pipeline
 */
class Pipeline
{
    private $queue;

    /**
     * Pipeline constructor.
     */
    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    /**
     * @param callable $middleware
     */
    public function pipe(callable $middleware) : void
    {
        $this->queue->enqueue($middleware);
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
        if (!$this->queue->isEmpty()) {
            return $default($request);
        };

        $current = $this->queue->dequeue();
        return $current($request, function (ServerRequestInterface $request) use ($default) {
            return $this->next($request, $default);
        });
    }

}