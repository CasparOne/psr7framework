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
    public function pipe($middleware) : void
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
        $delegate = new Next( clone $this->queue, $default);
        return $delegate($request);
    }
}