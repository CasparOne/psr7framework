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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        $delegate = new Next( clone $this->queue, $next);
        return $delegate($request, $response);
    }

    /**
     * @param callable $middleware
     */
    public function pipe($middleware) : void
    {
        $this->queue->enqueue($middleware);
    }
}